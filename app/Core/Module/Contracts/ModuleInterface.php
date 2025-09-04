<?php

namespace App\Core\Module\Contracts;

interface ModuleInterface
{
    public function getName(): string;
    
    public function getDisplayName(): string;
    
    public function getDescription(): string;
    
    public function getIcon(): string;
    
    public function getOrder(): int;
    
    public function isActive(): bool;
    
    public function getRoutes(): array;
    
    public function getPermissions(): array;
    
    public function getSidebarConfig(): array;
    
    public function getVersion(): string;
}