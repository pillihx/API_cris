<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTableCommand extends Command {

    protected $commandName = 'create:table';
    protected $commandDescription = "Create Table BD";

    protected $commandArgumentName = "name_table";
    protected $commandArgumentDescription = "Que tabla quieres crear";

    protected $commandHelp = "Este comando te permite crear una tabla con el nombre que tu le hayas pasado y que se encuentre en /bin/models/";

    /*protected $commandOptionName = "cap"; // should be specified like "app:greet John --cap"
    protected $commandOptionDescription = 'If set, it will greet in uppercase letters';  */ 

    protected function configure()
    {
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
        require __DIR__ . "/../app/bootstrap.php";
        //PARAMETROS DE LA ENTRADA
        $name_table = $input->getArgument($this->commandArgumentName);

        if(file_exists ( __DIR__ . "/../db/models/".$name_table.".php")){
            //TABLE STRUCT
            $table = require( __DIR__ . "/../db/models/".$name_table.".php");
            $container->cli->buildCreate("TABLE",$table);

            $response = "Tabla $name_table creada satisfactoriamente!!!";
        }
        else
            $response = "La Tabla $name_table no existe en /bin/models/";
        //OUTPUT CONSOLE
        $output->writeln($response);
    }
}