## Number Guessing Game
### Overview: 
  This project is a simple game hosted on infinityfree.net where the user attempts to guess a number between 1-100. After each guess, the user is given feedback of either “higher” or “lower” until the user guesses the correct number. 

### Technologies Used:
  This project consists of PHP, SQL, HTML, and JavaScript. Along with those languages, infinityfree.net was used to host the site, database, and the SSL Certificate for the authentication of users on the site. Session variables were implemented in order to store a user's information throughout the session, which allowed for a simple implementation of a high score display. This is a piece of what the session variable implementation looks like:

```html
    <p>You won!</p>
    <p>The number was: <?php echo $_SESSION["gameNumber"]?></p>
    <p><?php echo "You guessed the number in " . $_SESSION["guessCount"] . " guesses!"?></p>
```

### Technical Problem(s):
  A technical problem that I solved while developing this application was the process of securely handling user information via salting and hashing the password using SHA256. In the code snippet below, you can see my implementation for handling this data when a user creates an account:

```php
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
```

### My Contribution:
  This project was developed completely by me. I took care of the various elements such as setting up the database, coding the authentication and site, and developing the game and the surrounding logic. I went through the process of implementing SSL certificates into the site as well.
  
### Photos of Application:


![Image](GitHubPortfolioPics/guessingGameLogin.png)<br>
![Image](GitHubPortfolioPics/guessingGameGame.png)<br>
![Image](GitHubPortfolioPics/guessingGameHighScore.png)<br>


## Hangman Game
### Overview: 
  This project is the classic game of hangman where the user attempts to guess a secret word letter by letter, seeing how many incorrect guesses they accrue by the time they guess the word.

### Technologies Used:
  This project was created using .NET 6. In this project, we implemented web sockets to allow asynchronous communication between the client and server for a quality game experience. We also implemented authentication of user accounts in order to keep track of global high scores for all users using the user's account. On top of that, we incorporated a database to store the user information as well as the global scores for all users. This was done using SQLite. Using this, we were able to display the top scorers and immediately post a user’s score to the table upon the game finishing.

### Technical Problem(s):
  A technical problem I was able to solve surrounding this project was the use of web sockets to check when the user has guessed all the letters in the word. Using some debugging tools as well as doing some of my own research allowed to me understand the logic of the web sockets and get them to fully function. This is how I implemented it:
  
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

Another technical problem I had to overcome was learning the .NET framework while developing this. This was my first project using this Framework and C# in general. Through some research and using the programming knowledge I already had, I was able to implement this program in a new framework. One such section that was different to me was the OnPost method in the .cshtml.cs file attached to the .cshtml file. My implementation of this is shown below:

```csharp
public void OnPost(Models.SignUpModel su)
{
    string username = su.username;
    string password = su.password;

    string u = "";

    String connectionString = @"Data Source=C:\Users\codyb\Documents\Computer Science\SE2\HangmanRepo3\HangmanWeb\db\hangmanDB3.db";

    using (System.Data.SQLite.SQLiteConnection conn = new System.Data.SQLite.SQLiteConnection(connectionString))
    {
	conn.Open();

	var command = conn.CreateCommand();
	command.CommandText = @"SELECT username FROM Users WHERE username = $username";
	command.Parameters.AddWithValue("$username", username);

	using (var reader = command.ExecuteReader())
	{
	    while (reader.Read())
	    {
		u = reader.GetString(0);
	    }
	}

	if (u == "" && su.password != null)
	{
	    Random rand = new Random();
	    Byte[] b = new Byte[2];
	    rand.NextBytes(b);
	    string salt = "";
	    for (int i = 0; i < 2; i++)
	    {
		salt += b[i].ToString("X");
	    }
	    password += salt;

	    using (SHA256 sha256Hash = SHA256.Create())
	    {
		byte[] sourceBytes = Encoding.UTF8.GetBytes(password);
		byte[] hashBytes = sha256Hash.ComputeHash(sourceBytes);
		string hashedPass = BitConverter.ToString(hashBytes).Replace("-", String.Empty);


		var insCommand = conn.CreateCommand();
		insCommand.CommandText = @"INSERT INTO Users (username, password, salt) VALUES ($username, $password, $salt)";
		insCommand.Parameters.AddWithValue("$username", username);
		insCommand.Parameters.AddWithValue("$password", hashedPass);
		insCommand.Parameters.AddWithValue("$salt", salt);

		var ins = insCommand.ExecuteNonQuery();

		SessionVar.Username = username;
		Response.Redirect("https://localhost:7249/game.html");
	    }
	}
    }
}
```

### My Contribution:
  This project was developed with a team of 4. I primarily handled the authentication and user management as well as the web sockets. I dealt with salting and hashing of user information, validating user credentials and all the logic surrounding this.  I also handled the logic of passing information from the client to the server and back to the client asynchronously using web sockets. Along with this, I did contribute a bit with the game logic and the setup of the database, although those were primarily done by other members of the team.

### Photos of Application:

![Image](GitHubPortfolioPics/hangmanLogin.png)<br>
![Image](GitHubPortfolioPics/hangmanGame.png)<br>
![Image](GitHubPortfolioPics/hangmanScore.png)<br>


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
  This project had all 4 members of our team working on every aspect of the program. I implemented the initial setup for SignalR that my team and I all worked with in order to pass data. I also helped design the layout of the front-end with bootstrap, which I had picked up from various other college courses I had taken. Along with that, I had my hand in helping out with the game logic of the card game, which was fairly involved compared to some other projects I’ve done. Here is some of the game logic:

```csharp
else if (cardFromHand.Value == card.Value + 1 || cardFromHand.Value == card.Value - 1)
{
PlayStack1Stack.Push(cardFromHand);
for (int i = 0; i < hand.Count; i++)
{
    if (hand[i].Name.CompareTo(cardFromHand.Name) == 0)
    {
	pos = i;
	hand.RemoveAt(i);
	break;
    }
}
if (playerDrawStackStack.Count != 0)
{
    hand.Insert(pos, playerDrawStackStack.Pop());
}
else if (hand.Count == 0)
{
    res1 = 2;
    res2 = 1;
}
}
```

### Photos of Application:

![Image](GitHubPortfolioPics/speedPreGame.png)<br>
![Image](GitHubPortfolioPics/speedGameResult.png)<br>
![Image](GitHubPortfolioPics/speedGame.png)<br>


## Scholarship Application
### Overview:
  This project was developed for a Capstone project for a previous year and had some existing bugs that needed to be cleaned up as well as upgrades to .NET that needed to be implemented. Our team of 3 was able to resolve all the bugs tasked to us as well as find and fix a couple of others. These bugs ranged from adding extra fields to a scholarship application form, fixing a page not rendering when no data was present in the query, and also adding functionality into the code to allow a database to be automatically generated by just running a command. We also were able to upgrade the .NET 2 project all the way up to .NET 6.
