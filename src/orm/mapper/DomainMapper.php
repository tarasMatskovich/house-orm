<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 15:39
 */

namespace houseorm\mapper;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use houseorm\EntityManagerInterface;
use houseorm\gateway\builder\QueryBuilder;
use houseorm\gateway\builder\QueryBuilderInterface;
use houseorm\gateway\connection\InMemoryConnection;
use houseorm\gateway\connection\PdoConnection;
use houseorm\gateway\datatable\DataTableGateway;
use houseorm\gateway\datatable\request\QueryRequest;
use houseorm\gateway\GatewayInterface;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\collection\DomainCollection;
use houseorm\mapper\collection\DomainCollectionInterface;
use houseorm\mapper\object\DomainObjectInterface;

/**
 * Class DomainMapper
 * @package houseorm\mapper
 */
class DomainMapper implements DomainMapperInterface
{

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var GatewayInterface
     */
    protected $gateway;

    /**
     * @var
     */
    protected $reader;

    /**
     * @var string
     */
    protected $primaryKey;

    /**
     * @var array
     */
    private $mapping;

    /**
     * @var QueryBuilderInterface
     */
    private $builder;

    /**
     * @var string
     */
    private $target;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DomainMapper constructor.
     * @param string $entity
     * @param GatewayInterface|null $gateway
     * @param Reader|null $reader
     * @param string $primaryKey
     * @throws DomainMapperException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(
        string $entity,
        ?GatewayInterface $gateway = null,
        Reader $reader = null,
        string $primaryKey = 'id'
    )
    {
        $this->entity = $entity;
        $this->reader = ($reader) ? $reader : $this->getReader();
        $this->gateway = ($gateway) ? $gateway : $this->getGateway();
        $this->primaryKey = $primaryKey;
        $this->mapping = $this->getMapping();
        $this->builder = $this->getBuilder();
        $this->setTarget();
    }

    /**
     * @return QueryBuilderInterface
     */
    private function getBuilder()
    {
        return new QueryBuilder();
    }

    /**
     * @throws DomainMapperException
     */
    public function setTarget()
    {
        try {
            $gatewayParts = $this->getGatewayParts();
        } catch (\ReflectionException $e) {
            throw new DomainMapperException($this->entity);
        }
        $target = $gatewayParts[1] ?? null;
        $this->target = $target;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private function getGatewayParts()
    {
        $reflectionClass = new \ReflectionClass($this->entity);
        $gatewayAnnotation = $this->reader->getClassAnnotation($reflectionClass, Gateway::class);
        $gatewayType = $gatewayAnnotation->type;
        $gatewayParts = explode('.', $gatewayType);
        return $gatewayParts;
    }

    /**
     * @return GatewayInterface
     * @throws DomainMapperException
     */
    private function getGateway()
    {
        try {
            $gatewayParts = $this->getGatewayParts();
            if (!$gatewayParts && count($gatewayParts) !== 2) {
                throw new DomainMapperException($this->entity);
            }
            $gateway = $gatewayParts[0] ?? null;
            $gatewayObj = null;
            switch ($gateway) {
                case 'datatable':
                    $gatewayObj = new DataTableGateway(new PdoConnection());
                    break;
                default:
                    $gatewayObj = new DataTableGateway(new PdoConnection());
                    break;
            }
            return $gatewayObj;
        } catch (\ReflectionException $e) {
            throw new DomainMapperException($this->entity);
        }
    }

    /**
     * @return AnnotationReader
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    private function getReader()
    {
        $loader = require '../../../vendor/autoload.php';
        AnnotationRegistry::registerLoader(array($loader, "loadClass"));
        return new AnnotationReader();
    }

    /**
     * @return array
     * @throws DomainMapperException
     */
    private function getMapping()
    {
        try {
            $reflectionClass = new \ReflectionClass($this->entity);
            $privateProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
            $mapping = [];
            foreach ($privateProperties as $privateProperty) {
                $propertyAnnotation = $this->reader->getPropertyAnnotation($privateProperty, Field::class);
                $map = $propertyAnnotation->map;
                if ($map) {
                    $mapping[$privateProperty->getName()] = $map;
                }
            }
            return $mapping;
        } catch (\ReflectionException $e) {
            throw new DomainMapperException($this->entity);
        }
    }

    /**
     * @param array $result
     * @throws DomainMapperException
     * @return mixed
     */
    private function doMap(array $result)
    {
        if ($this->entity) {
            try {
                $entityReflectionClass = new \ReflectionClass($this->entity);
                $entityObject = $entityReflectionClass->newInstanceWithoutConstructor();
                $entityProperties = $entityReflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
                foreach ($entityProperties as $entityProperty) {
                    $propertyName = $entityProperty->getName();
                    if (array_key_exists($propertyName, $this->mapping) && array_key_exists($propertyName, $result)) {
                        $entityProperty->setAccessible(true);
                        $entityProperty->setValue($entityObject, $result[$this->mapping[$propertyName]]);
                        $entityProperty->setAccessible(false);
                    }
                }
                return $entityObject;
            } catch (\ReflectionException $e) {
                throw new DomainMapperException($this->entity);
            }
        }
    }

    /**
     * @param $entity
     * @return array
     * @throws \ReflectionException
     */
    private function reverseMap($entity)
    {
        $fields = [];
        $entityReflectionClass = new \ReflectionClass($entity);
        $privateProperties = $entityReflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        foreach ($privateProperties as $privateProperty) {
            $name = $privateProperty->getName();
            $privateProperty->setAccessible(true);
            $value = $privateProperty->getValue($entity);
            $privateProperty->setAccessible(false);
            if (isset($this->mapping[$name])) {
                $fields[$this->mapping[$name]] = $value;
            }
        }
        return $fields;
    }

    /**
     * @param $id
     * @return DomainObjectInterface|null
     */
    public function find($id)
    {
        $pk = $this->primaryKey;
        $query = $this->builder->getSelectQuery();
        $query->select(['*']);
        $query->from([$this->target]);
        $query->where([$pk => $id]);
        $query->limit(1);
        $queryRequest = new QueryRequest($query, $pk);
        $result = $this->gateway->execute($queryRequest);
        if (isset($result['result']) && !empty($result['result'])) {
            try {
                return $this->doMap($this->fetchOne($result['result']));
            } catch (DomainMapperException $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * @param array $result
     * @return array
     */
    private function fetchOne(array $result)
    {
        return $result[0] ?? [];
    }

    /**
     * @param array $criteria
     * @return DomainCollectionInterface
     */
    public function findBy($criteria)
    {
        $pk = $this->primaryKey;
        $query = $this->builder->getSelectQuery();
        $query->select(['*']);
        $query->from([$this->target]);
        $query->where($this->getCriteriaByMapping($criteria));
        $queryRequest = new QueryRequest($query, $pk);
        $result = $this->gateway->execute($queryRequest);
        $collection = new DomainCollection();
        if (isset($result['result']) && !empty($result['result'])) {
            foreach ($result['result'] as $res) {
                try {
                    $entity = $this->doMap($res);
                    $collection->add($entity);
                } catch (DomainMapperException $e) {
                }
            }
            return $collection;
        }
        return new DomainCollection();
    }

    /**
     * @param array $criteria
     * @return DomainObjectInterface|null
     */
    public function findOneBy($criteria)
    {
        $pk = $this->primaryKey;
        $query = $this->builder->getSelectQuery();
        $query->select(['*']);
        $query->from([$this->target]);
        $query->where($this->getCriteriaByMapping($criteria));
        $query->limit(1);
        $queryRequest = new QueryRequest($query, $pk);
        $result = $this->gateway->execute($queryRequest);
        if (isset($result['result']) && !empty($result['result'])) {
            try {
                return $this->doMap($this->fetchOne($result['result']));
            } catch (DomainMapperException $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * @param $entity
     * @return void
     */
    public function save(&$entity)
    {
        try {
            $fields = $this->reverseMap($entity);
        } catch (\ReflectionException $e) {
            return;
        }
        $lastInsertId = null;
        try {
            if ($this->isEntityExists($entity)) {
                $lastInsertId = $this->doUpdate($fields);
            } else {
                $lastInsertId = $this->doSave($fields);
            }
        } catch (\ReflectionException $e) {
            return;
        }
        if ($lastInsertId) {
            $refreshedEntity = $this->find($lastInsertId);
            $entity = $refreshedEntity;
        }
    }

    /**
     * @param array $attributes
     * @return int|null
     */
    private function doSave(array $attributes)
    {
        $query = $this->builder->getInsertQuery();
        $query->into([$this->target]);
        $attributes = $this->removePrimaryKeyFromFields($attributes);
        $query->fields($attributes);
        $queryRequest = new QueryRequest($query, $this->primaryKey);
        $this->gateway->execute($queryRequest);
        return $this->gateway->getLastInsertId();
    }

    /**
     * @param array $attributes
     * @return int|null
     */
    private function doUpdate(array $attributes)
    {
        $query = $this->builder->getUpdateQuery();
        $query->update([$this->target]);
        $pk = $this->retrieveMappedPrimaryKeyFromAttributes($attributes);
        $attributes = $this->removePrimaryKeyFromFields($attributes);
        $query->set($attributes);
        if ($pk) {
            $query->where([
                $this->primaryKey => $pk
            ]);
            $this->gateway->execute(new QueryRequest($query, $this->primaryKey));
            return $pk;
        }
        return null;
    }

    /**
     * @param array $attributes
     * @return int|null
     */
    private function retrieveMappedPrimaryKeyFromAttributes(array $attributes)
    {
        if (array_key_exists($this->primaryKey, $attributes)) {
            return $attributes[$this->primaryKey];
        }
        return null;
    }

    /**
     * @param $entity
     * @return bool
     * @throws \ReflectionException
     */
    private function isEntityExists($entity)
    {
        $entityReflectionClass = new \ReflectionClass($entity);
        $privateProperties = $entityReflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        foreach ($privateProperties as $property) {
            $field = $property->getName();
            if ((string)$field === (string)$this->primaryKey) {
                $property->setAccessible(true);
                $value = $property->getValue($entity);
                return (bool)$value && (bool)$this->find($value);
            }
        }
        return false;
    }

    /**
     * @param $entity
     */
    public function delete($entity)
    {
        $query = $this->builder->getDeleteQuery();
        try {
            $fields = $this->reverseMap($entity);
        } catch (\ReflectionException $e) {
            return;
        }
        $pk = $this->retrieveMappedPrimaryKeyFromAttributes($fields);
        if (isset($this->mapping[$this->primaryKey]) && null !== $pk && $this->target) {
            $query->from([$this->target]);
            $query->where([
                $this->mapping[$this->primaryKey] => $pk
            ]);
            $this->gateway->execute(new QueryRequest($query, $this->primaryKey));
        }
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        if (!$this->gateway->getConnection()->getConfig()) {
            $this->gateway->setConfigToConnection($em->getDefaultConfig());
        }
    }

    /**
     * @param array $fields
     * @return array
     */
    private function removePrimaryKeyFromFields(array $fields)
    {
        if (array_key_exists($this->primaryKey, $fields) && !($this->gateway->getConnection() instanceof InMemoryConnection)) {
            unset($fields[$this->primaryKey]);
        }
        return $fields;
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function getCriteriaByMapping(array $attributes)
    {
        $criteria = [];
        foreach ($this->mapping as $field => $map) {
            if (isset($attributes[$field])) {
                $criteria[$map] = $attributes[$field];
            }
        }
        return $criteria;
    }
}
