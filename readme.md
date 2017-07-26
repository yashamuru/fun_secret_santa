# PHP test for senior developers

This is a test for senior PHP backend developers.

Estimated time: 2 hours.

Once you finnish the exercise you should send the **full Git repository** by email.
You may use a [Git bundle](https://git-scm.com/docs/git-bundle).

## Getting started

Install all dependencies

```
composer install
```

### Prerequisites

* PHP 7.0 or higher
* composer
* Docker (optional)

## Exercise

Create a CLI application to generate lists to play Secret Santa.

* Using TDD is a plus
* A nice Git history is a plus

### What is Secret Santa

**Secret Santa** is a Western Christmas tradition in which members of a group or community are randomly
assigned a player to whom they give a gift. The identity of the gift giver is a secret not to be revealed.

### Input format

* The application must read the list of players from a text file.
* Each player must be in one line.
* The text file can't have repeated players.
* The file with players is located at /players.txt

### Assignations restrictions

* Each player must have one player to give the gift.
* Each player must receive a gift.
* You can not be assigned to yourself.
* The assignation must be random.

If any of this restrictions can not be satisfied the application should throw an Exception.

### Contracts

The application will need to interact with other systems. This means that some components
of the application need to adhere some already existing contracts (see /src/Contracts).

* Input: Reads the file with the players
* Output: Prints the output on screen (standard output)
* Random: Generates random numbers
* Game: The game itself

**This contracts can not be modified.**

### Sample input / output

#### Input (from file)

Alpha  
Charlie  
Bravo  
Foxtrot

#### Output (on screen)

Alpha -> Bravo  
Charlie -> Alpha  
Foxtrot -> Charlie  
Bravo -> Foxtrot