<?php
/**
 * Created by PhpStorm.
 * User: t.matskovich
 * Date: 10.12.2019
 * Time: 15:39
 */

namespace houseorm\mapper;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use houseorm\Cache\Request\Find\FindCacheRequest;
use houseorm\Cache\Request\Set\SetCacheRequest;
use houseorm\EntityManagerInterface;
use houseorm\EventManager\EventManager;
use houseorm\EventManager\EventManagerInterface;
use houseorm\EventManager\Events\Create\EntityCreated;
use houseorm\EventManager\Events\Delete\EntityDeleted;
use houseorm\EventManager\Events\Find\EntityFound;
use houseorm\EventManager\Events\Update\EntityUpdated;
use houseorm\gateway\builder\QueryBuilder;
use houseorm\gateway\builder\QueryBuilderInterface;
use houseorm\gateway\connection\factory\ConnectionFactoryInterface;
use houseorm\gateway\connection\InMemoryConnection;
use houseorm\gateway\datatable\DataTableGateway;
use houseorm\gateway\datatable\request\QueryRequest;
use houseorm\gateway\GatewayInterface;
use houseorm\mapper\annotations\Field;
use houseorm\mapper\annotations\Gateway;
use houseorm\mapper\annotations\Relation;
use houseorm\mapper\annotations\ViaRelation;
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
     * @var array
     */
    protected $listeners;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

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
     * @var array
     */
    private $relations;

    /**
     * @var QueryBuilderInterface
     */
    private $builder;

    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ConnectionFactoryInterface
     */
    private $connectionFactory;

    /**
     * DomainMapper constructor.
     * @param string $entity
     * @param array $listeners
     * @param GatewayInterface|null $gateway
     * @param Reader|null $reader
     * @param string $primaryKey
     * @throws DomainMapperException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(
        string $entity,
        array $listeners = [],
        ?GatewayInterface $gateway = null,
        Reader $reader = null,
        string $primaryKey = 'id'
    )
    {
        $this->entity = $entity;
        $this->listeners = $listeners;
        $this->reader = ($reader) ? $reader : $this->getReader();
        $this->gateway = $gateway;
        $this->primaryKey = $primaryKey;
        $this->mapping = $this->getMapping();
        $this->builder = $this->getBuilder();
        $this->setTarget();
        $this->eventManager = new EventManager();
        foreach ($this->listeners as $eventType => $listener) {
            $this->eventManager->listen($eventType, $listener);
        }
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
        $target = null;
        $scheme = null;
        if (count($gatewayParts) === 2) {
            $target = $gatewayParts[1] ?? null;
        } elseif(count($gatewayParts) === 3) {
            $scheme = $gatewayParts[1] ?? null;
            $target = $gatewayParts[2] ?? null;
        } else {
            throw new DomainMapperException($this->entity);
        }
        $this->target = $target;
        $this->scheme = $scheme;
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
            if (!$gatewayParts) {
                throw new DomainMapperException($this->entity);
            }
            $gateway = $gatewayParts[0] ?? null;
            $gatewayObj = null;
            $driver = $this->entityManager->getDefaultConfig()->getDriver();
            switch ($gateway) {
                case 'datatable':
                    $gatewayObj = new DataTableGateway($this->connectionFactory->getConnection($driver));
                    break;
                default:
                    $gatewayObj = new DataTableGateway($this->connectionFactory->getConnection($driver));
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
        return new AnnotationReader();
    }

    /**
     * @return array
     * @throws DomainMapperException
     */
    public function getMapping()
    {
        if ($this->mapping) {
            return $this->mapping;
        }
        try {
            $reflectionClass = new \ReflectionClass($this->entity);
            $viaAnnotations = [];
            $classAnnotations = $this->reader->getClassAnnotations($reflectionClass);
            foreach ($classAnnotations as $classAnnotation) {
                if (get_class($classAnnotation) === ViaRelation::class) {
                    $viaAnnotations[] = $classAnnotation;
                }
            }
            foreach ($viaAnnotations as $viaAnnotation) {
                $entity = $viaAnnotation->entity;
                $via = $viaAnnotation->via;
                $firstLocalKey = $viaAnnotation->firstLocalKey;
                $firstForeignKey = $viaAnnotation->firstForeignKey;
                $secondLocalKey = $viaAnnotation->secondLocalKey;
                $secondForeignKey = $viaAnnotation->secondForeignKey;
                if ($entity && $via && $firstLocalKey && $firstForeignKey && $secondForeignKey && $secondLocalKey) {
                    $this->relations[$entity] = [
                        'type' => 'via',
                        'via' => $via,
                        'firstLocalKey' => $firstLocalKey,
                        'firstForeignKey' => $firstForeignKey,
                        'secondLocalKey' => $secondLocalKey,
                        'secondForeignKey' => $secondForeignKey
                    ];
                }
            }
            $privateProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
            $mapping = [];
            foreach ($privateProperties as $privateProperty) {
                $propertyAnnotation = $this->reader->getPropertyAnnotation($privateProperty, Field::class);
                if ($propertyAnnotation) {
                    $map = $propertyAnnotation->map;
                    if ($map) {
                        $mapping[$privateProperty->getName()] = $map;
                        $relationAnnotation = $this->reader->getPropertyAnnotation($privateProperty, Relation::class);
                        if ($relationAnnotation) {
                            $entity = $relationAnnotation->entity;
                            $key = $relationAnnotation->key;
                            if ($entity && $key) {
                                $this->relations[$entity] = [
                                    'type' => 'simple',
                                    'key' => $key,
                                    'localKey' => $privateProperty->getName()
                                ];
                            }
                        }
                    }
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
                $attributes = [];
                foreach ($entityProperties as $entityProperty) {
                    $propertyName = $entityProperty->getName();
                    if (array_key_exists($propertyName, $this->mapping) && array_key_exists($this->mapping[$propertyName], $result)) {
                        $entityProperty->setAccessible(true);
                        $entityProperty->setValue($entityObject, $result[$this->mapping[$propertyName]]);
                        $entityProperty->setAccessible(false);
                        $attributes[$propertyName] = $result[$this->mapping[$propertyName]];
                    }
                }
                $eventManager = $this->entityManager->getEventManager();
                if ($eventManager) {
                    $eventManager->dispatch(EntityFound::EVENT_TYPE, new EntityFound($entityObject));
                }
                $mapperEventManager = $this->eventManager;
                if ($mapperEventManager) {
                    $mapperEventManager->dispatch(EntityFound::EVENT_TYPE, new EntityFound($entityObject));
                }
                $this->setCleanAttributes($entityObject, $attributes);
                return $entityObject;
            } catch (\ReflectionException $e) {
                throw new DomainMapperException($this->entity);
            }
        }
        return null;
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
            if (isset($this->mapping[$name]) && null !== $value) {
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
        $cache = $this->entityManager->getCache();
        if ($cache) {
            $result = $cache->get(new FindCacheRequest($this->target, $id));
            if ($result) {
                if (isset($result['entity'])) {
                    $clonedEntity = clone $result['entity'];
                    return $clonedEntity;
                } elseif(\is_string($result)) {
                    try {
                        return $this->doMap(json_decode($result, true));
                    } catch (DomainMapperException $e) {
                        return null;
                    }
                }
            }
        }
        $query = $this->builder->getSelectQuery();
        $query->select(['*']);
        $query->from([$this->makeTarget()]);
        $query->where([$pk => $id]);
        $query->limit(1);
        $queryRequest = new QueryRequest($query, $pk);
        $result = $this->gateway->execute($queryRequest);
        if (isset($result['result']) && !empty($result['result'])) {
            try {
                $entity = $this->doMap($this->fetchOne($result['result']));
                if ($cache) {
                    $cache->set(new SetCacheRequest($this->target, $id, $entity, $this->fetchOne($result['result'])));
                }
                return $entity;
            } catch (DomainMapperException $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * @return string
     */
    private function makeTarget()
    {
        return $this->scheme ? $this->scheme . '.' . $this->target : $this->target;
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
        $query->from([$this->makeTarget()]);
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
                    return new DomainCollection();
                }
            }
            return $collection;
        }
        return new DomainCollection();
    }

    /**
     * @param array $criteria
     * @return DomainObjectInterface|mixed|null
     */
    public function findOneBy($criteria)
    {
        $pk = $this->primaryKey;
        $query = $this->builder->getSelectQuery();
        $query->select(['*']);
        $query->from([$this->makeTarget()]);
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
     * @param $relativeEntityName
     * @return DomainCollectionInterface
     */
    public function findRelative($entity, $relativeEntityName)
    {
        if (isset($this->relations[$relativeEntityName])) {
            $relation = $this->relations[$relativeEntityName];
            $mapper = $this->entityManager->getMapper($relativeEntityName);
            if ($mapper) {
                $type = $relation['type'] ?? null;
                switch ($type) {
                    case 'via':
                        $viaEntity = $relation['via'] ?? null;
                        $firstLocalKey = $relation['firstLocalKey'] ?? null;
                        $firstForeignKey = $relation['firstForeignKey'] ?? null;
                        $secondLocalKey = $relation['secondLocalKey'] ?? null;
                        $secondForeignKey = $relation['secondForeignKey'] ?? null;
                        $result = new DomainCollection();
                        if ($viaEntity && $firstForeignKey && $firstLocalKey && $secondForeignKey && $secondLocalKey) {
                            $viaMapper = $this->entityManager->getMapper($viaEntity);
                            if ($viaEntity) {
                                $viaLocalKeyValue = $this->retrieveLocalKeyValue($entity, $firstLocalKey);
                                $viaEntities = $viaMapper->findBy([$firstForeignKey => $viaLocalKeyValue])->toArray();
                                // TODO: Maybe change on batch query (IN ...)
                                foreach ($viaEntities as $viaEntity) {
                                    $foreignKeyValue = $this->retrieveLocalKeyValue($viaEntity, $secondForeignKey);
                                    $resultEntity = $mapper->findOneBy([$secondLocalKey => $foreignKeyValue]);
                                    if ($resultEntity) {
                                        $result->add($resultEntity);
                                    }
                                }
                            }
                        }
                        return $result;
                        break;
                    case 'simple':
                    default:
                        $key = $relation['key'] ?? null;
                        $localKey = $relation['localKey'] ?? null;
                        if ($key && $localKey) {
                            $relativeEntityMapping = $mapper->getMapping();
                            $mappedKey = $relativeEntityMapping[$key] ?? null;
                            if ($mappedKey) {
                                $value = $this->retrieveLocalKeyValue($entity, $localKey);
                                return $mapper->findBy([$key => $value]);
                            }
                        }
                        break;
                }
            }
        }
        return new DomainCollection();
    }

    /**
     * @param $entity
     * @param $relativeEntityName
     * @return DomainObjectInterface|mixed|null
     */
    public function findRelativeOne($entity, $relativeEntityName)
    {
        if (isset($this->relations[$relativeEntityName])) {
            $relation = $this->relations[$relativeEntityName];
            $mapper = $this->entityManager->getMapper($relativeEntityName);
            if ($mapper) {
                $type = $relation['type'] ?? null;
                switch ($type) {
                    case 'via':
                        $viaEntity = $relation['via'] ?? null;
                        $firstLocalKey = $relation['firstLocalKey'] ?? null;
                        $firstForeignKey = $relation['firstForeignKey'] ?? null;
                        $secondLocalKey = $relation['secondLocalKey'] ?? null;
                        $secondForeignKey = $relation['secondForeignKey'] ?? null;
                        $result = null;
                        if ($viaEntity && $firstForeignKey && $firstLocalKey && $secondForeignKey && $secondLocalKey) {
                            $viaMapper = $this->entityManager->getMapper($viaEntity);
                            if ($viaEntity) {
                                $viaLocalKeyValue = $this->retrieveLocalKeyValue($entity, $firstLocalKey);
                                $viaEntity = $viaMapper->findOneBy([$firstForeignKey => $viaLocalKeyValue]);
                                $foreignKeyValue = $this->retrieveLocalKeyValue($viaEntity, $secondForeignKey);
                                $result = $mapper->findOneBy([$secondLocalKey => $foreignKeyValue]);
                            }
                        }
                        return $result;
                        break;
                    case 'simple':
                    default:
                        $key = $relation['key'] ?? null;
                        $localKey = $relation['localKey'] ?? null;
                        if ($key) {
                            $relativeEntityMapping = $mapper->getMapping();
                            $mappedKey = $relativeEntityMapping[$key] ?? null;
                            if ($mappedKey) {
                                $value = $this->retrieveLocalKeyValue($entity, $localKey);
                                return $mapper->findOneBy([$key => $value]);
                            }
                        }
                        break;
                }
            }
        }
        return null;
    }

    /**
     * @param $entity
     * @param $relativeEntityName
     * @param $criteria
     * @return DomainCollectionInterface
     */
    public function findRelativeBy($entity, $relativeEntityName, $criteria)
    {
        if (isset($this->relations[$relativeEntityName])) {
            $relation = $this->relations[$relativeEntityName];
            $mapper = $this->entityManager->getMapper($relativeEntityName);
            if ($mapper) {
                $type = $relation['type'] ?? null;
                switch ($type) {
                    case 'via':
                        $viaEntity = $relation['via'] ?? null;
                        $firstLocalKey = $relation['firstLocalKey'] ?? null;
                        $firstForeignKey = $relation['firstForeignKey'] ?? null;
                        $secondLocalKey = $relation['secondLocalKey'] ?? null;
                        $secondForeignKey = $relation['secondForeignKey'] ?? null;
                        $result = new DomainCollection();
                        if ($viaEntity && $firstForeignKey && $firstLocalKey && $secondForeignKey && $secondLocalKey) {
                            $viaMapper = $this->entityManager->getMapper($viaEntity);
                            if ($viaEntity) {
                                $viaLocalKeyValue = $this->retrieveLocalKeyValue($entity, $firstLocalKey);
                                $viaEntities = $viaMapper->findBy([$firstForeignKey => $viaLocalKeyValue])->toArray();
                                foreach ($viaEntities as $viaEntity) {
                                    $foreignKeyValue = $this->retrieveLocalKeyValue($viaEntity, $secondForeignKey);
                                    $resultEntity = $mapper->findOneBy(array_merge($criteria, [$secondLocalKey => $foreignKeyValue]));
                                    if ($resultEntity) {
                                        $result->add($resultEntity);
                                    }
                                }
                            }
                        }
                        return $result;
                        break;
                    case 'simple':
                    default:
                        $key = $relation['key'] ?? null;
                        $localKey = $relation['localKey'] ?? null;
                        if ($key && $localKey) {
                            $relativeEntityMapping = $mapper->getMapping();
                            $mappedKey = $relativeEntityMapping[$key] ?? null;
                            if ($mappedKey) {
                                $value = $this->retrieveLocalKeyValue($entity, $localKey);
                                $criteria = array_merge([$key => $value], $criteria);
                                return $mapper->findBy($criteria);
                            }
                        }
                        break;
                }
            }
        }
        return new DomainCollection();
    }

    /**
     * @param $entity
     * @param $relativeEntityName
     * @param $criteria
     * @return DomainObjectInterface|mixed|null
     */
    public function findRelativeOneBy($entity, $relativeEntityName, $criteria)
    {
        if (isset($this->relations[$relativeEntityName])) {
            $relation = $this->relations[$relativeEntityName];
            $mapper = $this->entityManager->getMapper($relativeEntityName);
            if ($mapper) {
                $type = $relation['type'] ?? null;
                switch ($type) {
                    case 'via':
                        $viaEntity = $relation['via'] ?? null;
                        $firstLocalKey = $relation['firstLocalKey'] ?? null;
                        $firstForeignKey = $relation['firstForeignKey'] ?? null;
                        $secondLocalKey = $relation['secondLocalKey'] ?? null;
                        $secondForeignKey = $relation['secondForeignKey'] ?? null;
                        $result = null;
                        if ($viaEntity && $firstForeignKey && $firstLocalKey && $secondForeignKey && $secondLocalKey) {
                            $viaMapper = $this->entityManager->getMapper($viaEntity);
                            if ($viaEntity) {
                                $viaLocalKeyValue = $this->retrieveLocalKeyValue($entity, $firstLocalKey);
                                $viaEntity = $viaMapper->findOneBy([$firstForeignKey => $viaLocalKeyValue]);
                                $foreignKeyValue = $this->retrieveLocalKeyValue($viaEntity, $secondForeignKey);
                                $result = $mapper->findOneBy(array_merge($criteria, [$secondLocalKey => $foreignKeyValue]));
                            }
                        }
                        return $result;
                        break;
                    case 'simple':
                    default:
                        $key = $relation['key'] ?? null;
                        $localKey = $relation['localKey'] ?? null;
                        if ($key) {
                            $relativeEntityMapping = $mapper->getMapping();
                            $mappedKey = $relativeEntityMapping[$key] ?? null;
                            if ($mappedKey) {
                                $value = $this->retrieveLocalKeyValue($entity, $localKey);
                                $criteria = array_merge([$key => $value], $criteria);
                                return $mapper->findOneBy($criteria);
                            }
                        }
                        break;
                }
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
        $eventType = null;
        try {
            if ($this->isEntityExists($entity)) {
                $fields = $this->makeOnlyDirtyAttributes($entity, $fields);
                $lastInsertId = $this->doUpdate($fields);
                $eventType = EntityUpdated::EVENT_TYPE;
                $payload = new \StdClass();
                $payload->entity = $entity;
                $payload->target = $this->target;
                $payload->pk = $lastInsertId;
                $this->entityManager->getEventManager()->dispatch($eventType, new EntityUpdated($payload));
            } else {
                $lastInsertId = $this->doSave($fields);
                $eventType = EntityCreated::EVENT_TYPE;
            }
        } catch (\ReflectionException $e) {
            return;
        }
        if ($lastInsertId) {
            $refreshedEntity = $this->find($lastInsertId);
            $entity = $refreshedEntity;
            if ($eventType && $this->entityManager->getEventManager()) {
                if (EntityCreated::EVENT_TYPE === $eventType) {
                    $this->entityManager->getEventManager()->dispatch($eventType, new EntityCreated($entity));
                }
            }
            $mapperEventManager = $this->eventManager;
            if ($mapperEventManager) {
                if (EntityCreated::EVENT_TYPE === $eventType) {
                    $mapperEventManager->dispatch($eventType, new EntityCreated($entity));
                }
                if (EntityUpdated::EVENT_TYPE === $eventType) {
                    $mapperEventManager->dispatch($eventType, new EntityUpdated($entity));
                }
            }
        }
    }

    /**
     * @param $entity
     * @param $relativeEntityName
     */
    public function saveRelative(&$entity, $relativeEntityName)
    {
        if (null === $relativeEntityName) {
            return;
        }
        $mapper = $this->entityManager->getMapper($relativeEntityName);
        if (null !== $mapper) {
            $mapper->save($entity);
        }
    }

    /**
     * @param array $attributes
     * @return int|null
     */
    private function doSave(array $attributes)
    {
        $query = $this->builder->getInsertQuery();
        $query->into([$this->makeTarget()]);
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
        $query->update([$this->makeTarget()]);
        $pk = $this->retrieveMappedPrimaryKeyFromAttributes($attributes);
        $attributes = $this->removePrimaryKeyFromFields($attributes);
        $query->set($attributes);
        if ($attributes) {
            $query->where([
                $this->primaryKey => $pk
            ]);
            $this->gateway->execute(new QueryRequest($query, $this->primaryKey));
        }
        return $pk;
    }

    /**
     * @param $entity
     * @param array $attributes
     * @return array
     */
    private function makeOnlyDirtyAttributes($entity, array $attributes)
    {
        if (!isset($entity->__cleanAttributes) || !$entity->__cleanAttributes || !is_array($entity->__cleanAttributes)) {
            return $attributes;
        }
        $dirtyAttributes = [];
        foreach ($attributes as $key => $value) {
            if (isset($entity->__cleanAttributes[$key]) && ($entity->__cleanAttributes[$key] !== $value || $key === $this->primaryKey)) {
                $dirtyAttributes[$key] = $value;
            }
        }
        return $dirtyAttributes;
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
     * @param $localKey
     * @return mixed
     */
    private function retrieveLocalKeyValue($entity, $localKey)
    {
        try {
            $entityReflectionClass = new \ReflectionClass($entity);
            $privateProperties = $entityReflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
            foreach ($privateProperties as $property) {
                $field = $property->getName();
                if ((string)$field === (string)$localKey) {
                    $property->setAccessible(true);
                    $value = $property->getValue($entity);
                    $property->setAccessible(false);
                    return $value;
                }
            }
        } catch (\ReflectionException $e) {
            return null;
        }
        return null;
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
            $query->from([$this->makeTarget()]);
            $query->where([
                $this->mapping[$this->primaryKey] => $pk
            ]);
            $res = $this->gateway->execute(new QueryRequest($query, $this->primaryKey));
            if (isset($res['result']) && $res['result']) {
                $eventManager = $this->entityManager->getEventManager();
                if ($eventManager) {
                    $eventManager->dispatch(EntityDeleted::EVENT_TYPE, new EntityDeleted($entity));
                }
                $mapperEventManager = $this->eventManager;
                if ($mapperEventManager) {
                    $mapperEventManager->dispatch(EntityDeleted::EVENT_TYPE, new EntityDeleted($entity));
                }
            }
        }
    }

    /**
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->connectionFactory = $this->entityManager->getConnectionFactory();
        $this->gateway = $this->getGateway();
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

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $entity
     * @param array $attributes
     */
    private function setCleanAttributes(&$entity, array $attributes = [])
    {
        $entity->__cleanAttributes = $attributes;
    }
}
