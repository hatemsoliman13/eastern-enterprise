## prerequisite  

you need to install:-  
    - Docker  
    - Docker Compose  
    - make  

## Installation  

Run the following commands.  
    - make up  
    - Make composer-install  

## Usage  

After running the installation commands you can start and stop the project using.  
    - make up (starting the project)  
    - run-addresses-distance-processor (running the main command)  
    - make test (running tests)  
    - make down (stopping the project)  

Please note that the command uses  
    - 'data/addresses/input/addresses.txt' as (default input file)  
    - 'data/addresses/output/addresses.csv' as (default output file)  
you can simply change the input and output path by specifying the input and output file path to the make command  
Example:  
    - make run-addresses-distance-processor INPUT_FILE_PATH=/path/to/input/file OUTPUT_FILE_PATH=/path/to/output/file  


Please note that the command expects the main address to be at the first line of the file and all addresses following the same format.  