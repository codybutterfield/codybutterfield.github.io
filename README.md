## Welcome to GitHub Pages

You can use the [editor on GitHub](https://github.com/codybutterfield/codybutterfield.github.io/edit/main/README.md) to maintain and preview the content for your website in Markdown files.

Whenever you commit to this repository, GitHub Pages will run [Jekyll](https://jekyllrb.com/) to rebuild the pages in your site, from the content in your Markdown files.

### Markdown

Markdown is a lightweight and easy-to-use syntax for styling your writing. It includes conventions for

```markdown
Syntax highlighted code block

# Header 1
## Header 2
### Header 3

- Bulleted
- List

1. Numbered
2. List

**Bold** and _Italic_ and `Code` text

[Link](url) and ![Image](src)
```

For more details see [Basic writing and formatting syntax](https://docs.github.com/en/github/writing-on-github/getting-started-with-writing-and-formatting-on-github/basic-writing-and-formatting-syntax).

### Jekyll Themes

Your Pages site will use the layout and styles from the Jekyll theme you have selected in your [repository settings](https://github.com/codybutterfield/codybutterfield.github.io/settings/pages). The name of this theme is saved in the Jekyll `_config.yml` configuration file.

### Support or Contact

Having trouble with Pages? Check out our [documentation](https://docs.github.com/categories/github-pages-basics/) or [contact support](https://support.github.com/contact) and we’ll help you sort it out.



## Number Guessing Game
### Overview: 
  This project is a simple game hosted on infinityfree.net where the user attempts to guess a number between 1-100. After each guess, the user is given feedback of either “higher” or “lower” until the user guesses the correct number. 

### Technologies Used:
  This project consists of PHP, SQL, HTML, and JavaScript. Along with those languages, infinityfree.net was used to host the site, database, and the SSL Certificate for the authentication of users on the site. Session variables were also implemented in order to store a user's information throughout the session, which allowed for a simple implementation of a high score display.

### Technical Problem(s):
  A technical problem that I solved while developing this application was the process of securely handling user information via salting and hashing the password using SHA256. In the code snippet below, you can see my implementation for the solution:

```php
<?php
    session_start();

    $username = $_GET["username"];
	$password = $_GET["password"];

    $dbservername = "sql204.epizy.com";
    $dbusername = "epiz_30809346";
    $dbpassword = "lIY5xtN9yw8p";
    $dbname = "epiz_30809346_guessing_game";
    
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlCheckUser = "SELECT username FROM Users WHERE Username = '" . $username . "'";
    $resultCheckUser = $conn->query($sqlCheckUser);
    if ($resultCheckUser->num_rows > 0) {
        echo "Need a unique username";
    } else {
        $sqlCheckSalt = "SELECT salt FROM Users";
        $resultCheckSalt = $conn->query($sqlCheckSalt);

        do {
            $salt = bin2hex(random_bytes(2));
            $existingSalt = "";
            while ($row = $resultCheckSalt->fetch_assoc()) {
                if ($salt == $row["salt"]) {
                    $existingSalt = $salt;
                    break;
                }
            }
        } while ($existingSalt == $salt);

        $hashedPass = hash('sha256', $password . $salt);
        $sqlCreateAcct = "INSERT INTO Users (username, password, salt) VALUES ('" . $username . "', '" . $hashedPass . "', '" . $salt . "')";
        if ($conn->query($sqlCreateAcct) === TRUE){
            $_SESSION["username"] = $username;
            echo '<script type="text/javascript">',
             'redirectGameSetup();',
             '</script>';
        } else {
            echo "Error: " . $sqlCreateAcct . "<br>" . $conn->error;
        }
    }
    $conn->close();
?>
```

### My Contribution:
  This project was developed completely by me. I took care of the various elements such as setting up the database, coding the authentication and site, and developing the game and the surrounding logic. I went through the process of implementing SSL certificates into the site as well.


## Hangman Game
### Overview: 
  This project is the classic game of hangman where the user attempts to guess a secret word letter by letter, seeing how many incorrect guesses they accrue by the time they guess the word.

### Technologies Used:
  This project was created using .NET 6. In this project, we implemented web sockets to allow asynchronous communication between the client and server for a quality game experience. We also implemented authentication of user accounts in order to keep track of global high scores for all users using the user's account. On top of that, we incorporated a database to store the user information as well as the global scores for all users. This was done using SQLite. Using this, we were able to display the top scorers and immediately post a user’s score to the table upon the game finishing.

### Technical Problem(s):
  A technical problem I was able to solve surrounding this project was the use of web sockets. Using some debugging tools as well as doing some research allowed to me understand the logic of the web sockets and get them to fully function. This is one place this was implemented:
  
 ```csharp
if (game.CheckResult())
{
    Array.Clear(buffer, 0, buffer.Length);
    for (int i = 0; i < gameWinMsg.Length; i++)
    {
	buffer[i] = (byte)gameWinMsg[i];
    }
    SessionVar.Score = game.GetLives();
}

await webSocket.SendAsync(new ArraySegment<byte>(buffer, 0, buffer.Length), result.MessageType, result.EndOfMessage, CancellationToken.None);

result = await webSocket.ReceiveAsync(new ArraySegment<byte>(buffer), CancellationToken.None);
```

### My Contribution:
  This project was developed with a team of 4. I primarily handled the authentication and user management as well as the web sockets. I dealt with salting and hashing of user information, validating user credentials and all the logic surrounding this.  I also handled the logic of passing information from the client to the server and back to the client asynchronously using web sockets. Along with this, I did contribute a bit with the game logic and the setup of the database, although those were primarily done by other members of the team.


## Speed Game
### Overview: 
  This project is the card game known as “Speed”. In this two-player game, both players try to get rid of the cards in their hand and stack by playing cards on two different stacks in the middle of the playing field. In order to play a card, the value of the card being played must be one more or one less than the card in the playing field. The first player to get rid of their hand and their stack wins.

### Technologies Used:
  This project was made using the .NET 6. Signal R was also used for asynchronous communication between clients and the server to provide an excellent experience for a game that is fast-paced and requires immediate feedback. My team and I also used a lot of JavaScript as well as bootstrap for the front end of the application and to pass certain data to the server (such as cards that both players need to see).

### Technical Problem(s):
  A technical problem I solved in this project was handling the “win condition” for the game. We needed logic to check that if the player’s hand and their playing stack were empty, then we needed to declare them the winner, and along with that declare the other player the loser.
	Another problem I had solved was handling the game starting. We wanted the game to start only when there were 2 players connected to our server and the play button was activated. This wasn’t too hard to implement, but upon them connecting, I had to assign them both to a unique variable that would allow us to send out the initial data they needed to start the game. This code looks like this:
	
```csharp
if (UserHandler.ConnectedIds.Count == 2)
{
	int count = 0;
	string p1 = "";
	string p2 = "";
	foreach (string a in UserHandler.ConnectedIds)
	{
	    if (count == 0)
	    {
		p1 = a;
		count++;
	    }
	    else
	    {
		p2 = a;
	    }
	}
	
	//Code to initialize the game here, left out of this snippet for clarity in showing the solution to the technical problem I discussed
	
	await Clients.Client(p1).SendAsync("CreateGame", p1Hand, playerStack2.getHand().Count, ds1, drawStack2.getDraw().Count, ps1, ps2, es1, es2, psTop1, psTop2);
        await Clients.Client(p2).SendAsync("CreateGame", p2Hand, playerStack1.getHand().Count, ds2, drawStack1.getDraw().Count, ps1, ps2, es1, es2, psTop1, 		psTop2);
}
```

### My Contribution:
  This project had all 4 members of our team working on every aspect of the program. I implemented the initial setup for SignalR that my team and I all worked with in order to pass data. I also helped design the layout of the front-end with bootstrap, which I had picked up from various other college courses I had taken. Along with that, I had my hand in helping out with the game logic of the card game, which was fairly involved compared to some other projects I’ve done.
  
![Image]()


## Scholarship Application
### Overview:
  This project was developed for a Capstone project for a previous year and had some existing bugs that needed to be cleaned up as well as upgrades to .NET that needed to be implemented. Our team of 3 was able to resolve all the bugs tasked to us as well as find and fix a couple of others. These bugs ranged from adding extra fields to a scholarship application form, fixing a page not rendering when no data was present in the query, and also adding functionality into the code to allow a database to be automatically generated by just running a command. We also were able to upgrade the .NET 2 project all the way up to .NET 6.
