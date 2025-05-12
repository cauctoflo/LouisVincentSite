<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleViewCommand extends Command
{
    protected $signature = 'module:make-view {name : The name of the view} 
                            {module : The module name}
                            {--resource : Generate a set of resource views (index, create, edit, show)}';
    
    protected $description = 'Create a new view within a module';

    public function handle()
    {
        $viewName = $this->argument('name');
        $moduleName = $this->argument('module');
        
        // Check if module exists
        $modulePath = app_path("Modules/{$moduleName}");
        if (!File::exists($modulePath)) {
            $this->error("Module {$moduleName} does not exist! Create it first using module:create command.");
            return 1;
        }
        
        // Create views directory if it doesn't exist
        $viewsPath = "{$modulePath}/Views";
        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
        }
        
        // Create view directory based on the name
        $viewDirectory = $viewsPath;
        if (Str::contains($viewName, '.')) {
            $parts = explode('.', $viewName);
            $fileName = array_pop($parts);
            $nestedPath = implode('/', $parts);
            $viewDirectory = "{$viewsPath}/{$nestedPath}";
            
            if (!File::exists($viewDirectory)) {
                File::makeDirectory($viewDirectory, 0755, true);
            }
        } else {
            $fileName = $viewName;
        }
        
        if ($this->option('resource')) {
            $this->createResourceViews($moduleName, $viewDirectory, $fileName);
        } else {
            $this->createView($moduleName, $viewDirectory, $fileName);
        }
        
        return 0;
    }
    
    protected function createView($moduleName, $viewDirectory, $fileName)
    {
        $viewPath = "{$viewDirectory}/{$fileName}.blade.php";
        
        // Check if view already exists
        if (File::exists($viewPath)) {
            $this->error("View {$fileName} already exists in module {$moduleName}!");
            return;
        }
        
        $stub = <<<EOT
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('$fileName') }}</div>

                <div class="card-body">
                    <!-- Your content here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;
        
        File::put($viewPath, $stub);
        $this->info("View {$fileName} created successfully in module {$moduleName}.");
    }
    
    protected function createResourceViews($moduleName, $viewDirectory, $resourceName)
    {
        $resourceViews = [
            'index' => $this->getIndexViewStub($resourceName),
            'create' => $this->getCreateViewStub($resourceName),
            'edit' => $this->getEditViewStub($resourceName),
            'show' => $this->getShowViewStub($resourceName),
        ];
        
        foreach ($resourceViews as $view => $content) {
            $viewPath = "{$viewDirectory}/{$view}.blade.php";
            
            if (File::exists($viewPath)) {
                $this->warn("View {$resourceName}.{$view} already exists, skipping.");
                continue;
            }
            
            File::put($viewPath, $content);
            $this->info("View {$resourceName}.{$view} created successfully in module {$moduleName}.");
        }
    }
    
    protected function getIndexViewStub($resourceName)
    {
        $title = Str::title(Str::plural($resourceName));
        $resourceVar = Str::camel(Str::plural($resourceName));
        $resourceSingular = Str::singular($resourceName);
        
        return <<<EOT
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('$title') }}</span>
                    <a href="{{ route('$resourceName.create') }}" class="btn btn-sm btn-primary">Create New</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\$$resourceVar as \$$resourceSingular)
                                <tr>
                                    <td>{{ \$$resourceSingular->id }}</td>
                                    <td>{{ \$$resourceSingular->name }}</td>
                                    <td>{{ \$$resourceSingular->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('$resourceName.show', \$$resourceSingular->id) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('$resourceName.edit', \$$resourceSingular->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('$resourceName.destroy', \$$resourceSingular->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;
    }
    
    protected function getCreateViewStub($resourceName)
    {
        $title = "Create " . Str::title($resourceName);
        
        return <<<EOT
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('$title') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('$resourceName.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ \$message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Add more form fields here -->

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                                <a href="{{ route('$resourceName.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;
    }
    
    protected function getEditViewStub($resourceName)
    {
        $title = "Edit " . Str::title($resourceName);
        $resourceVar = Str::camel($resourceName);
        
        return <<<EOT
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('$title') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('$resourceName.update', \$$resourceVar->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', \$$resourceVar->name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ \$message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Add more form fields here -->

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                                <a href="{{ route('$resourceName.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;
    }
    
    protected function getShowViewStub($resourceName)
    {
        $title = Str::title($resourceName) . " Details";
        $resourceVar = Str::camel($resourceName);
        
        return <<<EOT
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('$title') }}</span>
                    <a href="{{ route('$resourceName.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label class="col-md-4 col-form-label text-md-right font-weight-bold">{{ __('ID') }}</label>
                        <div class="col-md-6">
                            <span class="form-control-plaintext">{{ \$$resourceVar->id }}</span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-4 col-form-label text-md-right font-weight-bold">{{ __('Name') }}</label>
                        <div class="col-md-6">
                            <span class="form-control-plaintext">{{ \$$resourceVar->name }}</span>
                        </div>
                    </div>

                    <!-- Add more fields here -->

                    <div class="form-group row mb-3">
                        <label class="col-md-4 col-form-label text-md-right font-weight-bold">{{ __('Created At') }}</label>
                        <div class="col-md-6">
                            <span class="form-control-plaintext">{{ \$$resourceVar->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-4 col-form-label text-md-right font-weight-bold">{{ __('Updated At') }}</label>
                        <div class="col-md-6">
                            <span class="form-control-plaintext">{{ \$$resourceVar->updated_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{ route('$resourceName.edit', \$$resourceVar->id) }}" class="btn btn-primary">
                                {{ __('Edit') }}
                            </a>
                            <form action="{{ route('$resourceName.destroy', \$$resourceVar->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;
    }
} 