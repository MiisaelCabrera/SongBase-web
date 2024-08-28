<?php
    require_once "php/connection.php";

    class CAD
    {
        public $connection;
        private $error;

        static public function addUser($name, $lastname, $username, $mail, $password)
        {
            $connection = new Connection();
            $userExists = $connection->connect()->prepare("SELECT username FROM user WHERE username = '$username'");
            $mailExists = $connection->connect()->prepare("SELECT mail FROM user WHERE mail = '$mail'");
            if($userExists->execute() && $mailExists->execute())
            {
                $usernameFound = $userExists->fetch(PDO::FETCH_NUM);
                $mailFound = $mailExists->fetch(PDO::FETCH_NUM);
                if($usernameFound)
                {
                    return 2;
                }
                else if($mailFound)
                {
                    return 3;
                }
                else
                {
                    $keywords = $name." ".$lastname." ".$username;
                    $query = $connection->connect()->prepare("INSERT INTO user (username, name, lastname, mail, password, id, keywords) VALUES ('$username', '$name', '$lastname', '$mail', '$password', '0', '$keywords')");
                    $follow = $connection->connect()->prepare("INSERT INTO followers (follows, followed) VALUES ('$username', '$username')");
                    if($query -> execute() )
                    {
                        $follow->execute();
                        session_start();
                        $_SESSION['name'] = $name;
                        $_SESSION['username'] = $username;
                        header("Location: EditProfile.php");
                    }
                    else
                    {
                        return 4;
                    }
                }
            }
        }

        static public function verifyUser($username, $password)
        {
            $connection = new Connection();
            $query = $connection->connect()->prepare("SELECT * FROM user WHERE username = '$username' and password = '$password' ");
            if($query->execute())
            {
                $user = $query->fetch(PDO::FETCH_NUM);
                if($user)
                {
                    session_start();
                    $_SESSION['name'] = $user[1];
                    $_SESSION['username'] = $user[0];
                    header("Location: TimeLine.php");
                }
                else
                {
                    return 2;
                }
            }
            else
            {
                return 3;
            }
        }

        static public function addImage($image, $tmp_name, $description)
        {
            $connection = new Connection();

            $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'image'");
            if($getLastId->execute())
            {
                $username = $_SESSION['username'];
                $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
                $id = $lastIdInfo[0];
                $image = $image.$id.".png";
                $description = ucfirst($description);
                $query = $connection->connect()->prepare("INSERT INTO image (image, username) VALUES ('$image', '$username')");
                if($query -> execute())
                {
                    if(move_uploaded_file($tmp_name, $image))
                    {
                        $date = date('Y/m/d');
                        $dateFormat = "YYYY/MM/DD";
                        $assignPictureToAnUser = $connection->connect()->prepare("UPDATE user SET id = '$id' WHERE username = '$username'");
                        if($assignPictureToAnUser -> execute())
                        {
                            $createPost = $connection->connect()->prepare("INSERT INTO post (id, text, date, author, type) VALUES ('$id', '$description', '$date', '$username', 'prof')");
                            if($createPost -> execute())
                                header("Location: MyProfile.php");
                        }
                    }
                }
                else
                {
                    return 1;
                }
            }
            
        }

        static public function addPostImage($image, $tmp_name, $text)
        {
            $connection = new Connection();

            $text = ucfirst($text);

            $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'image'");
            if($getLastId->execute())
            {
                $username = $_SESSION['username'];
                $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
                $id = $lastIdInfo[0];
                $image = $image.$id.".png";
                $query = $connection->connect()->prepare("INSERT INTO image (image, username) VALUES ('$image', '$username')");
                if($query -> execute())
                {
                    if(move_uploaded_file($tmp_name, $image))
                    {
                        $date = date('Y/m/d');
                        $dateFormat = "YYYY/MM/DD";
                        $createPost = $connection->connect()->prepare("INSERT INTO post (id, date, text, author) VALUES ('$id', '$date', '$text', '$username')");
                        if($createPost -> execute())
                        { 
                            header("Location: MyProfile.php");
                        }
                    }
                }
                else
                {
                    return 1;
                }
            }
            
        }

        static public function postText($text)
        {
            $connection = new Connection();
            $text = ucfirst($text);
            $username = $_SESSION['username'];
            $date = date('Y/m/d');
            $dateFormat = "YYYY/MM/DD";
            $createPost = $connection->connect()->prepare("INSERT INTO post (date, text, author) VALUES ('$date', '$text', '$username')");
            if($createPost -> execute())
            {
                header("Location: MyProfile.php");
            
            }
                        
            
            
        }

        static public function postTimeLine($text)
        {
            $connection = new Connection();
            $text = ucfirst($text);
            $username = $_SESSION['username'];
            $date = date('Y/m/d');
            $dateFormat = "YYYY/MM/DD";
            $createPost = $connection->connect()->prepare("INSERT INTO post (date, text, author) VALUES ('$date', '$text', '$username')");
            if($createPost -> execute())
            {
                header("Location: TimeLine.php");
            
            }
                        
            
            
        }

        static public function postCommunity($text, $community)
        {
            $connection = new Connection();
            $text = ucfirst($text);
            $username = $_SESSION['username'];
            echo $username;
            $date = date('Y/m/d');
            $dateFormat = "YYYY/MM/DD";
            $createPost = $connection->connect()->prepare("INSERT INTO post (date, text, author, community) VALUES ('$date', '$text', '$username', '$community')");
            if($createPost -> execute())
            {
                header("Location: Community.php?community=$community");
            
            }
                        
            
            
        }

        static public function changeDescription($text)
        {
            $text = ucfirst($text);
            $connection = new Connection();
            $username = $_SESSION['username'];
            $query = $connection->connect()->prepare("UPDATE user SET description = '$text' WHERE username = '$username'");
            if($query -> execute())
            {
                header("Location: MyProfile.php");
            }
        }

        static public function getProfilePicture()
        {
            $connection = new Connection();
            $username = $_SESSION['username'];

            
            $query = $connection->connect()->prepare("SELECT id FROM user WHERE username = '$username'");
            if($query -> execute())
            { 
                $userDataId = $query->fetch(PDO::FETCH_NUM);
                $id = $userDataId[0];
                $getImageLocation = $connection->connect()->prepare("SELECT image FROM image WHERE id = '$id'");
                if($getImageLocation -> execute())
                {
                    $imageData = $getImageLocation->fetch(PDO::FETCH_NUM);
                    return $imageData[0];
                }
            }
        }
        
        static public function getProfileData()
        {
            $connection = new Connection();
            $username = $_SESSION['username'];
    
            $query = $connection->connect()->prepare("SELECT * FROM user WHERE username = '$username'");
            if($query -> execute())
            { 
                $userData = $query->fetch(PDO::FETCH_NUM);
                
                if($userData)
                    return $userData;

            
            }
    
        }

        static public function getUserData($username)
        {
            $connection = new Connection();
    
            $query = $connection->connect()->prepare("SELECT * FROM user WHERE username = '$username'");
            if($query -> execute())
            { 
                $userData = $query->fetch(PDO::FETCH_NUM);
                
                if($userData)
                    return $userData;

            
            }
    
        }

        static public function getUserImage($id)
        {
            $connection = new Connection();
    
            $query = $connection->connect()->prepare("SELECT image FROM image WHERE id = '$id'");
            if($query -> execute())
            { 
                $imageData = $query->fetch(PDO::FETCH_NUM);
                return $imageData[0];

            
            }
    
        }

        static public function getUserImageId($user)
        {
            $connection = new Connection();
    
            $query = $connection->connect()->prepare("SELECT id FROM user WHERE username = '$user'");
            if($query -> execute())
            { 
                $imageData = $query->fetch(PDO::FETCH_NUM);
                
                return $imageData[0];

            
            }
    
        }

        static public function checkIfFollow($followed)
        {
            $connection = new Connection();
            
            $follower = $_SESSION['username'];

            $query = $connection->connect()->prepare("SELECT * FROM followers WHERE follows ='$follower' AND followed = '$followed'");
            if($query -> execute())
            {
                $following = $query->fetch(PDO::FETCH_NUM);
                if($following)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }

        static public function follow($followed)
        {
            $connection = new Connection();
            
            $follower = $_SESSION['username'];

            $query = $connection->connect()->prepare("INSERT INTO followers (follows, followed) VALUES ('$follower', '$followed')");
            if($query -> execute())
                return true;
        }

        static public function unfollow($followed)
        {
            $connection = new Connection();
            
            $follower = $_SESSION['username'];

            $query = $connection->connect()->prepare("DELETE FROM followers WHERE follows = '$follower' AND followed = '$followed'");
            if($query -> execute())
                return true;
        }

        static public function followers($user)
        {
            $connection = new Connection();
            
            $query = $connection->connect()->prepare("SELECT COUNT(followed) FROM `followers` WHERE `followed` = '$user'");
            if($query -> execute())
            {
                
                $followers = $query->fetch(PDO::FETCH_NUM);
                return $followers[0];

            }
        }

        static public function following($user)
        {
            $connection = new Connection();
            
            $query = $connection->connect()->prepare("SELECT COUNT(follows) FROM `followers` WHERE `follows` = '$user'");
            if($query -> execute())
            {
                
                $followers = $query->fetch(PDO::FETCH_NUM);
                return $followers[0];

            }
        }

        static public function searchUsers($search)
        {
            $connection = new Connection();
            $users = NULL;

            $query = $connection->connect()->prepare("SELECT username, name, lastname, id FROM user WHERE keywords LIKE '%$search%'");
            if($query -> execute())
            {
                
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $users[] = $row;
                }

                return ($users);

            }
        }

        static public function searchSongs($search, $type)
        {
            $connection = new Connection();
            $songs = NULL;

            $query = $connection->connect()->prepare("SELECT id, name, creator, song, idImage FROM song WHERE (name LIKE '%$search%' OR creator LIKE '%$search%' )AND type = '$type' ");
            if($query -> execute())
            {
                
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $songs[] = $row;
                }

                return ($songs);

            }
        }

        static public function searchCommunities($search)
        {
            $connection = new Connection();
            $communities = NULL;

            $query = $connection->connect()->prepare("SELECT * FROM community WHERE (name LIKE '%$search%' OR admin LIKE '%$search%' )");
            if($query -> execute())
            {
                
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $communities[] = $row;
                }

                return ($communities);

            }
        }

        static public function checkIfList($song)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("SELECT * FROM list WHERE username ='$user' AND id = '$song'");
            if($query -> execute())
            {
                $onList = $query->fetch(PDO::FETCH_NUM);
                if($onList)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }

        static public function addToList($song)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("INSERT INTO list (username, id) VALUES ('$user', '$song')");
            if($query -> execute())
                return true;

        }

        static public function quitFromList($song)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("DELETE FROM list WHERE username = '$user' AND id = '$song'");
            if($query -> execute())
                return true;
        }

        static public function getSongsWithLetter($letter, $type)
        {
            $connection = new Connection();
            $songs = NULL;

            $query = $connection->connect()->prepare("SELECT * FROM song WHERE name LIKE '$letter%' AND type = '$type'");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $songs[] = $row;
                }

                return ($songs);
            }
        }

        static public function getPostsFromUser($user)
        {
            $connection = new Connection();
            $posts = NULL;
            $idposts = NULL;
            $query = $connection->connect()->prepare("SELECT * FROM post WHERE author = '$user' AND community IS NULL");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $posts[] = $row;
                }
                return ($posts);
            }
        }

        static public function getImage($id)
        {
            $connection = new Connection();

            $query = $connection->connect()->prepare("SELECT image FROM image WHERE id = '$id'");
            if($query -> execute())
            {
                $imageData = $query->fetch(PDO::FETCH_NUM);

                return ($imageData[0]);
            }
        }

        static public function likesCount($post)
        {
            $connection = new Connection();
            
            $query = $connection->connect()->prepare("SELECT COUNT(idpost) FROM `likes` WHERE `idpost` = '$post'");
            if($query -> execute())
            {
                
                $likes = $query->fetch(PDO::FETCH_NUM);
                return $likes[0];

            }
        }

        static public function checkIfLike($post)
        {
            $connection = new Connection();
            $username = $_SESSION['username'];
            
            $query = $connection->connect()->prepare("SELECT * FROM `likes` WHERE  `username` = '$username' && `idpost` = '$post'");
            if($query -> execute())
            {
                
                $likes = $query->fetch(PDO::FETCH_NUM);
                if($likes)
                    return true;
                else
                    return false;

            }
        }

        static public function like($post)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("INSERT INTO likes (username, idpost) VALUES ('$user', '$post')");
            if($query -> execute())
                return true;
        }

        static public function dislike($post)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("DELETE FROM likes WHERE username = '$user' AND idpost = '$post'");
            if($query -> execute())
                return true;
        }

        static public function deletePost($post)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];
            $query = $connection->connect()->prepare("SELECT * FROM post WHERE idpost = '$post'");
            if($query->execute())
            {
                $postInfo = $query->fetch(PDO::FETCH_NUM);
                if($postInfo[1] != "")
                {
                    $imageId = $postInfo[1];
                    $imageData = $connection->connect()->prepare("SELECT image FROM image WHERE id = '$imageId'");
                    $imageData->execute();
                    $postInfo = $imageData->fetch(PDO::FETCH_NUM);
                    $location = $postInfo[0];
                    

                    $status=unlink($location);   

                    $delete = $connection->connect()->prepare("DELETE FROM image WHERE id = '$imageId'");
                    $delete->execute();
                }
                if($postInfo[3] != "")
                {
                    $songId = $postInfo[4];
                    $songData = $connection->connect()->prepare("SELECT song FROM song WHERE id = '$songId'");
                    $songData->execute();
                    $postInfo = $songData->fetch(PDO::FETCH_NUM);
                    $location = $postInfo[0];
                    

                    $status=unlink($location);   

                    $delete = $connection->connect()->prepare("DELETE FROM song WHERE id = '$imageId'");
                    $delete->execute(); 
                }
                $delete = $connection->connect()->prepare("DELETE FROM post WHERE idpost = '$post'");
                if($delete -> execute())
                   return true; 
            }
        }

        static public function getImagesFromUser($user)
        {
            $connection = new Connection();
            $posts = NULL;
            $idposts = NULL;
            $query = $connection->connect()->prepare("SELECT image FROM image WHERE username = '$user'");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $posts[] = $row;
                }
                return ($posts);
            }
        }

        static public function getSongsByUser($user)
        {
            $connection = new Connection();
            $songs = NULL;
            $query = $connection->connect()->prepare("SELECT * FROM song WHERE creator = '$user'");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $songs[] = $row;
                }
                return ($songs);
            }
        }

        static public function getSong($id)
        {
            $connection = new Connection();

            $query = $connection->connect()->prepare("SELECT * FROM song WHERE id = '$id'");
            if($query -> execute())
            {
                $songData = $query->fetch(PDO::FETCH_NUM);

                return ($songData);
            }
        }

        static public function getScaleName($id)
        {
            $connection = new Connection();

            $query = $connection->connect()->prepare("SELECT name FROM scale WHERE id = '$id'");
            if($query -> execute())
            {
                $songData = $query->fetch(PDO::FETCH_NUM);

                return ($songData[0]);
            }
        }

        static public function getSongPost($id)
        {
            $connection = new Connection();

            $query = $connection->connect()->prepare("SELECT idpost FROM post WHERE idsong = '$id'");
            if($query -> execute())
            {
                $idPost = $query->fetch(PDO::FETCH_NUM);

                return ($idPost[0]);
            }
        }
        
        static public function getChordsFromScale($scale)
        {
            $connection = new Connection();
            $chords = NULL;
            $query = $connection->connect()->prepare("SELECT chord FROM chordscale WHERE scale = '$scale'");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $chords[] = $row;
                }
                return ($chords);
            }
        }

        static public function getChordData($id)
        {
            $connection = new Connection();

            $query = $connection->connect()->prepare("SELECT * FROM chord WHERE id = '$id'");
            if($query -> execute())
            {
                $chordData = $query->fetch(PDO::FETCH_NUM);

                return ($chordData);
            }
        }

        static public function getTimeLinePosts()
        {
            $connection = new Connection();
            $user = $_SESSION['username'];
            $postResults=NULL;
            $query = $connection->connect()->prepare("SELECT idpost, post.id, text, idsong, date, type, user.username, user.name, user.lastname FROM post INNER JOIN user INNER JOIN followers WHERE  post.author = user.username AND user.username = followers.followed and followers.follows = '$user' AND post.community IS NULL  ORDER BY idpost");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $postResults[] = $row;
                }
                return ($postResults);
            }
        }

        static public function getSongsWithLetterFromMyList($letter)
        {
            $connection = new Connection();
            $songs = NULL;
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("SELECT song.id, name, creator, song, idimage, type FROM song INNER JOIN list WHERE song.id = list.id AND name LIKE '$letter%' AND list.username = '$user'");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $songs[] = $row;
                }

                return ($songs);
            }
        }

        static public function getScales()
        {
            $connection = new Connection();
            $scales = NULL;

            $query = $connection->connect()->prepare("SELECT * FROM scale");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $scales[] = $row;
                }

                return ($scales);
            }
        }

        static public function addSongImage($image, $tmp_name)
        {
            $connection = new Connection();

            $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'image'");
            if($getLastId->execute())
            {
                $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
                $id = $lastIdInfo[0];
                $image = $image.$id.".png";
                $query = $connection->connect()->prepare("INSERT INTO image (image) VALUES ('$image')");
                if($query -> execute())
                {
                    if(move_uploaded_file($tmp_name, $image))
                    {
                        return $id;
                    }
                }
                else
                {
                    return 1;
                }
            }
            
        }

        static public function addPostSong($name, $instrument, $scale, $type, $imgId, $file, $tmp_name, $description)
        {
            $connection = new Connection();

            $instrument = ucfirst($instrument);
            $description = ucfirst($description);

            $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'song'");
            if($getLastId->execute())
            {
                $username = $_SESSION['username'];
                $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
                $id = $lastIdInfo[0];
                $file = $file.$id.".txt";
                $query = $connection->connect()->prepare("INSERT INTO song (name, creator, song, idimage, type, scale, instrument) VALUES ('$name', '$username','$file','$imgId','$type','$scale','$instrument')");
                if($query -> execute())
                {
                    if(move_uploaded_file($tmp_name, $file))
                    {
                        $date = date('Y/m/d');
                        $dateFormat = "YYYY/MM/DD";
                        $addToList = $connection->connect()->prepare("INSERT INTO list (username, id) VALUES ('$username', '$id')");
                        $createPost = $connection->connect()->prepare("INSERT INTO post ( text, idsong, date, author) VALUES ('$description', '$id', '$date', '$username')");
                        if($createPost -> execute() && $addToList -> execute())
                        { 
                            header("Location: Song.php?song=$id");
                        }
                    }
                }
                else
                {
                    return 1;
                }
            }
            
        }

        static public function getCommunityPosts($community)
        {
            $connection = new Connection();
            $user = $_SESSION['username'];
            $postResults=NULL;
            $query = $connection->connect()->prepare("SELECT idpost, post.id, text, idsong, date, type, user.username, user.name, user.lastname FROM post INNER JOIN user INNER JOIN usercommunity WHERE post.author = user.username AND user.username = usercommunity.username AND usercommunity.id = '$community' AND post.community = '$community' ORDER BY idpost;");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $postResults[] = $row;
                }
                return ($postResults);
            }
        }

        static public function isOnCommunity($community)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("SELECT * FROM usercommunity WHERE username ='$user' AND id = '$community'");
            if($query -> execute())
            {
                $found = $query->fetch(PDO::FETCH_NUM);
                if($found)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }

        
        static public function getCommunityData($community)
        {
            $connection = new Connection();
    
            $query = $connection->connect()->prepare("SELECT * FROM community WHERE id = '$community'");
            if($query -> execute())
            { 
                $communityData = $query->fetch(PDO::FETCH_NUM);
                
                if($communityData)
                    return $communityData;

            
            }
    
        }

        static public function joinCommunity($community)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("INSERT INTO usercommunity (username, id) VALUES ('$user', '$community')");
            if($query -> execute())
                return true;
        }

        static public function leaveCommunity($community)
        {
            $connection = new Connection();
            
            $user = $_SESSION['username'];

            $query = $connection->connect()->prepare("DELETE FROM usercommunity WHERE username = '$user' AND id = '$community'");
            if($query -> execute())
                return true;
        }
        
        static public function addCommunityPostImage($image, $tmp_name, $text, $community)
        {
            $connection = new Connection();

            $text = ucfirst($text);

            $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'image'");
            if($getLastId->execute())
            {
                $username = $_SESSION['username'];
                $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
                $id = $lastIdInfo[0];
                $image = $image.$id.".png";
                $query = $connection->connect()->prepare("INSERT INTO image (image, username) VALUES ('$image', '$username')");
                if($query -> execute())
                {
                    if(move_uploaded_file($tmp_name, $image))
                    {
                        $date = date('Y/m/d');
                        $dateFormat = "YYYY/MM/DD";
                        $createPost = $connection->connect()->prepare("INSERT INTO post (id, date, text, author, community) VALUES ('$id', '$date', '$text', '$username', '$community')");
                        if($createPost -> execute())
                        { 
                            header("Location: Community.php?community=".$community);
                        }
                    }
                }
                else
                {
                    return 1;
                }
            }
            
        }

        static public function addCommunityPostSong($name, $instrument, $scale, $type, $imgId, $file, $tmp_name, $description, $community)
        {
            $connection = new Connection();

            $instrument = ucfirst($instrument);
            $description = ucfirst($description);

            $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'song'");
            if($getLastId->execute())
            {
                $username = $_SESSION['username'];
                $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
                $id = $lastIdInfo[0];
                $file = $file.$id.".txt";
                $query = $connection->connect()->prepare("INSERT INTO song (name, creator, song, idimage, type, scale, instrument) VALUES ('$name', '$username','$file','$imgId','$type','$scale','$instrument')");
                if($query -> execute())
                {
                    if(move_uploaded_file($tmp_name, $file))
                    {
                        $date = date('Y/m/d');
                        $dateFormat = "YYYY/MM/DD";
                        $addToList = $connection->connect()->prepare("INSERT INTO list (username, id) VALUES ('$username', '$id')");
                        $createPost = $connection->connect()->prepare("INSERT INTO post ( text, idsong, date, author, community) VALUES ('$description', '$id', '$date', '$username', '$community')");
                        if($createPost -> execute() && $addToList -> execute())
                        { 
                            header("Location: Song.php?song=$id");
                        }
                    }
                }
                else
                {
                    return 1;
                }
            }
            
        }

        static public function getCommunityWithLetter($letter)
        {
            $connection = new Connection();
            $community = NULL;

            $query = $connection->connect()->prepare("SELECT * FROM community WHERE name LIKE '$letter%' ");
            if($query -> execute())
            {
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $community[] = $row;
                }

                return ($community);
            }
        }

        static public function addCommunity($name, $imgId, $description)
    {
        $connection = new Connection();

        $description = ucfirst($description);

        $getLastId = $connection->connect()->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'songbase' AND TABLE_NAME = 'community'");
        if($getLastId->execute())
        {
            $username = $_SESSION['username'];
            $lastIdInfo = $getLastId->fetch(PDO::FETCH_NUM);
            $date = date('Y/m/d');
            $dateFormat = "YYYY/MM/DD";
            $id = $lastIdInfo[0];
            $query = $connection->connect()->prepare("INSERT INTO community (name, creationDate, admin, imageid, description) VALUES ('$name', '$date','$username','$imgId','$description')");
            if($query -> execute())
            {
                    $addToList = $connection->connect()->prepare("INSERT INTO usercommunity (username, id) VALUES ('$username', '$id')");
                    if($addToList -> execute())
                    { 
                        header("Location: Community.php?community=$id");
                    }
                
            }
            else
            {
                return 1;
            }
        }
        
    }

    static public function deleteCommunity($id)
        {
            $connection = new Connection();
            

            $query = $connection->connect()->prepare("DELETE FROM community WHERE id = '$id' ");
            if($query -> execute())
                header("Location: TimeLine.php");
        }


    }

    
    

    


?>