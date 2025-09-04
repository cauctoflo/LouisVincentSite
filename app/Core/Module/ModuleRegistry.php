<?php

namespace App\Core\Module;

use App\Core\Module\Contracts\ModuleInterface;
use Illuminate\Support\Collection;

class ModuleRegistry
{
    private static array $modules = [];

    public static function register(ModuleInterface $module): void
    {
        self::$modules[$module->getName()] = $module;
    }

    public static function getModule(string $name): ?ModuleInterface
    {
        return self::$modules[$name] ?? null;
    }

    public static function getAllModules(): Collection
    {
        return collect(self::$modules);
    }

    public static function getActiveModules(): Collection
    {
        return collect(self::$modules)->filter(function ($module) {
            return $module->isActive();
        });
    }

    public static function getModulesForSidebar(): Collection
    {
        return self::getActiveModules()
            ->sortBy('order')
            ->map(function ($module) {
                return $module->getSidebarConfig();
            });
    }

    public static function hasModule(string $name): bool
    {
        return isset(self::$modules[$name]);
    }
}