<?php

namespace App\Core\Module;

use App\Core\Module\Contracts\ModuleInterface;
use Illuminate\Support\Facades\File;

abstract class BaseModule implements ModuleInterface
{
    protected string $name;
    protected string $displayName;
    protected string $description;
    protected string $icon;
    protected int $order = 100;
    protected string $version = '1.0.0';
    protected array $permissions = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function isActive(): bool
    {
        $configPath = storage_path("app/modules/" . strtolower($this->getName()) . "/config.json");
        
        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true) ?? [];
            return ($config['status'] ?? 'inactive') === 'active';
        }
        
        return false;
    }

    public function getSidebarConfig(): array
    {
        return [
            'name' => $this->getName(),
            'displayName' => $this->getDisplayName(),
            'icon' => $this->getIcon(),
            'order' => $this->getOrder(),
            'routes' => $this->getRoutes(),
            'permissions' => $this->getPermissions(),
            'isActive' => $this->isActive()
        ];
    }

    abstract public function getRoutes(): array;
}