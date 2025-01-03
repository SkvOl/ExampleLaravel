<?php
namespace App\Http\sources\User;

trait UserRights
{
    private static $rights = [];

    private static $formattedRights = [];

    /** @var int $isAdm Метка, админ ли человек, который делает запрос */
    private static $isAdm;

    /**
     * Принимает массив прав в формате []
     * @return void
     * @static
     */
    public static function initialization(array $rights, bool $isAdm): void
    {
        self::setRights($rights);
        self::$isAdm = $isAdm;
    }

    private static function updateFormattedRights(): void
    {
        self::$formattedRights = [];

        foreach (self::$rights as $value) {
            if (!key_exists($value['model_id'], self::$formattedRights))
                self::$formattedRights[$value['model_id']] = [];

            self::$formattedRights[$value['model_id']][] = $value;
        }
    }

    private static function setRights(array $rights): void
    {
        self::$rights = $rights;
        self::updateFormattedRights();
    }

    /**
     * Возвращает все права переданные в контструктор
     * @return array права
     * @static
     */
    public static function getRights(): array
    {
        return self::$rights;
    }

    /**
     * Возвращает форматированный вид прав, двумерный массив, первый ключ которого является id модели
     * @return array
     * @static
     */
    public static function getFormattedRights(): array
    {
        return self::$formattedRights;
    }

    /**
     * Возвращает права для конкретной модели
     * @param Model $model название модели
     * @return array
     * @static
     */
    public static function getRightsByModelId($model): array
    {
        if (key_exists($model::$model_id, self::$formattedRights)) {
            return self::$formattedRights[$model::$model_id];
        } else {
            return [];
        }
    }

    /**
     * Возвращает все права для конкретной модели конкретных уровней
     * @param Model $model название модели
     * @param array $level_rights интересуемые уровни прав
     * @return array
     * @static
     */
    public static function getRightsByLevel($model, array $level_rights): array
    {
        $rights = self::getRightsByModelId($model);

        return array_filter($rights, function ($it) use ($level_rights) {
            return in_array($it['level_rights_model'], $level_rights);
        });
    }


    /**
     * Проверяет есть ли один из уровней прав для модели
     * @param Model $model название модели
     * @param array $level_rights интересуемые уровни прав
     * @return bool
     * @static
     */
    public static function checkRightsByLevel($model, array $level_rights): bool
    {
        $rights = self::getRightsByModelId($model);

        foreach ($rights as $value) {
            if(in_array($value['level_rights_model'], $level_rights)) return true;
        }
        
        return false OR self::$isAdm;
    }


    /**
     * Возвращает все права для конкретной модели конкретных подразделений
     * @param Model $model название модели
     * @param array $departments интересуемые подразделения
     * @return array
     * @static
     */
    public static function getRightsByDepartment($model, array $departments): array
    {
        $rights = self::getRightsByModelId($model);
        
        return array_filter($rights, function ($it) use ($departments) {
            return in_array($it['department_id'], $departments);
        });
    }

    /**
     * Возвращае тправа для конкретных подразделений и конкретных урвоней прав 
     * @param Model $model название модели
     * @param array $level_rights интересуемые уровни прав
     * @param array $departments интересуемые подразделения
     * @return array
     * @static
     */
    public static function getRightsByLevelAndDepartment($model, array $level_rights, array $departments): array
    {
        $rights = self::getRightsByModelId($model);
        
        return array_filter($rights, function ($it) use ($level_rights, $departments) {
            return in_array($it['level_rights_model'], $level_rights) && in_array($it['department_id'], $departments);
        });
    }

    /**
     * Получить максимальный уровень прав для модели
     * @param Model $model название модели
     * @return int
     * @static
     */
    public static function getMaxRightsByModelId($model): int
    {
        return array_reduce(
            self::getRightsByModelId($model),
            function ($carry, $it) {
                return max($it['level_rights_model'], $carry);
            },
            0
        );
    }
}
