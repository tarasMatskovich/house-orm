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
use houseorm\gateway\builder\QueryBuilder;
use houseorm\gateway\builder\QueryBuilderInterface;
use houseorm\gateway\connection\PdoConnection;
use houseorm\gateway\datatable\DataTableGateway;
use houseorm\gateway\GatewayInterface;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Gateway;
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
    }

    /**
     * @return QueryBuilderInterface
     */
    private function getBuilder()
    {
        return new QueryBuilder();
    }

    /**
     * @return GatewayInterface
     * @throws DomainMapperException
     */
    private function getGateway()
    {
        try {
            $reflectionClass = new \ReflectionClass($this->entity);
            $gatewayAnnotation = $this->reader->getClassAnnotation($reflectionClass, Gateway::class);
            $gatewayType = $gatewayAnnotation->type;
            $gatewayParts = explode('.', $gatewayType);
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
            $target = $gatewayParts[1] ?? null;
            $this->target = $target;
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
     * @param $id
     * @return DomainObjectInterface
     */
    public function find($id)
    {
        $pk = $this->primaryKey;
        $query = $this->builder->getSelectQuery();
        $query->select(['*']);
        $query->from([$this->target]);
        $query->where([$pk => $id]);
        $query->limit(1);
        $result = $this->gateway->execute($query);
        return $this->doMap($result);
    }

    /**
     * @param array $criteria
     * @return DomainObjectInterface[]
     */
    public function findBy($criteria)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * @param array $criteria
     * @return DomainObjectInterface
     */
    public function findOneBy($criteria)
    {
        // TODO: Implement findOneBy() method.
    }
}
