    <?php
    //This will be used both to show ALL users, and to show an induvidual user!
    //You will get the user id by a form input in the url. something like www.thewall.com/users?ID=2
    //This will then be used so get the correct information from the user profile.
    if (isset($_GET['ID'])){

        $userID = $_GET['id'];

        //here is where the users page will be created if there is a $_GET post AND if it matches any users in the DB!
        //We need to check if they exist and return an error if they don't!

    }
    else{

        //Here is where we will display ALL the users! We could use $_POST here to make you able to search among the users.

    };
    /**
     * Created by PhpStorm.
     * User: Linus
     * Date: 2017-11-08
     * Time: 15:41
     */