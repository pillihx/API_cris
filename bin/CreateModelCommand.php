<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModelCommand extends Command {

    protected $commandName = 'create:model';
    protected $commandDescription = "Crear Modelo BD";
    protected $commandHelp = "Este comando permite crear tu modelo con las tablas que se encuentran en /bin/models/*.php";

    /*protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "";*/

    /*protected $commandOptionName = "cap"; // should be specified like "app:greet John --cap"
    protected $commandOptionDescription = 'If set, it will greet in uppercase letters';  */ 

    protected function configure(){
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->setHelp($this->commandHelp)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        require(__DIR__ . "/../app/bootstrap.php");
        //PARAMETROS DE LA ENTRADA
        $name = $input->getArgument($this->commandArgumentName);
        //SCHEMA QUE DEFINE LA BD
        $schema = require( __DIR__ . "/../db/schema.php");
        $container->cli->buildSchema($schema);

        //OUTPUT CONSOLE
        $output->writeln("Modelo ".$schema['dbname']." creado satisfactoriamente!!!");
    }
}