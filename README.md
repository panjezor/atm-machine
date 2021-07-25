Task 1


Task 2
As soon as you download this project from git, you should run 
```composer install``` to install dependencies.
Then run ```php artisan test``` or ```php artisan test --parallel``` (around same speed).

The output that you expected from the brief is in the ATMTest.php on method testCanGiveManifestoOutput.

You interact with the app by using 'receive' method of DataReceivingService.
This should be an array of strings like expected there.

I have not put any HTTP or console endpoints into the app.

So you'd know, I am mentoring a friend and I used this project as an opportunity to explain some things about Laravel and PHP, so I totally did not use Git. 


Task 3
For User stories I prefer the one that fits the principles of BDD, and fortunately, the example on wikipedia is sufficient for the cases I used to write at work.
I believe that the way the JBehave Java framework operates is one of the cleanest solutions to assure that what the client wants is actually met.

https://en.wikipedia.org/wiki/Behavior-driven_development#Behavioral_specifications

https://jbehave.org/
