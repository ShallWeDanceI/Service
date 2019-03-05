<?php

namespace DTK\Service\Commands;

use Illuminate\Console\Command;

class Service extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {service}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create service';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function handle() {
        $serviceName = $this->argument('service');
        $serviceNamespace = 'App\Services';
        
        $serviceNameParts = explode('\\', $serviceName);
        $serviceName = array_pop($serviceNameParts);
        
        if (count($serviceNameParts)) {
            $serviceNamespace .= DIRECTORY_SEPARATOR . implode('\\', $serviceNameParts);
        }
        
        $fileContent = <<<EOT
<?

namespace {$serviceNamespace};

use App\Services\Service;

class {$serviceName}Service implements Service {
    public function execute() {
    
    }
}

EOT;
        
        $basePath = 'app' . DIRECTORY_SEPARATOR . 'Services';
        
        if (!file_exists($basePath)) {
            mkdir($basePath);
        }
        
        foreach ($serviceNameParts as $serviceNamePart) {
            $basePath .= DIRECTORY_SEPARATOR . $serviceNamePart;
    
            if (!file_exists($basePath)) {
                mkdir($basePath);
            }
        }
        
        $file = $basePath . DIRECTORY_SEPARATOR . $serviceName . 'Service.php';
        
        file_put_contents($file, $fileContent);
    }
}
