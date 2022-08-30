<html>
<head>
    <title>DBtest</title>
    <style  type="text/css" media="screen"> 
    ul  li{ 
        list-style-type:none;
    } 
</style>
</head>
<body>
    <form method="post" action="index.php?go" id="searchform">
        <p><input type='text' name='name' size='70' placeholder='Введите текст поиска'> <input type='submit' name='submit' value='Найти'></p>
    </form>
    <p><a  href="?by=A">A</a> | <a  href="?by=B">B</a> | <a  href="?by=K">K</a></p>
    <?php
        $conn = mysqli_connect("127.0.0.1:3306", "root", "root", "dbtitlecom");
        if ($conn == true) {
            echo("<script>console.log('подключено');</script>");
        }
        else{
            echo("<script>console.log('не подключено');</script>");
        }

        $url = curl_init("https://jsonplaceholder.typicode.com/posts");
        $url1 = curl_init("https://jsonplaceholder.typicode.com/comments");
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '\post.txt', 'wb');
        $fp1 = fopen($_SERVER['DOCUMENT_ROOT'] . '\comments.txt', 'wb');
        curl_setopt($url, CURLOPT_FILE, $fp);
        curl_setopt($url1, CURLOPT_FILE, $fp1);
        curl_setopt($url, CURLOPT_HEADER, 0);
        curl_setopt($url1, CURLOPT_HEADER, 0);
        curl_exec($url);
        curl_exec($url1);
        curl_close($url);
        curl_close($url1);
        fclose($fp);
        fclose($fp1);

        $post = file('post.txt');
        $result = count($post);
        #echo $result;
        for($ui = 2, $i = 3, $t = 4, $b = 5, $index = 0; $ui < $result && $i < $result && $t < $result && $b < $result && $index < 100; $ui += 6, $i += 6, $t += 6, $b += 6, $index++){
            $userid[$index] = $post[$ui];
            $id[$index] = $post[$i];
            $title[$index] = $post[$t];
            $body[$index] = $post[$b];
        }
        $comments = file('comments.txt');
        $comresult = count($comments);
        #echo $comresult;
        for($index = 0, $pi = 2, $i = 3, $n = 4, $e = 5, $b = 6; $index < 500 && $pi < $comresult && $i < $comresult && $n < $comresult && $e < $comresult && $b < $comresult; $index++, $pi += 7, $i += 7, $n += 7, $e += 7, $b += 7){
            $postid[$index] = $comments[$pi];
            $comid[$index] = $comments[$i];
            $name[$index] = $comments[$n];
            $email[$index] = $comments[$e];
            $combody[$index] = $comments[$b];
        }
        $postidres = count($postid);
        $resultcomment = 0;
        for($index = 0; $index < $postidres; $index++){
            $postid[$index] = trim($postid[$index], '"postId": ,');
            $postid[$index] = substr($postid[$index], 0, -2);

            $comid[$index] = trim($comid[$index], '"id": ');
            $comid[$index] = substr($comid[$index], 0, -2);

            $name[$index] = trim($name[$index], '"name": "');
            $name[$index] = substr($name[$index], 0, -3);

            $email[$index] = trim($email[$index], '"email": "');
            $email[$index] = substr($email[$index], 0, -3);

            $combody[$index] = trim($combody[$index], '"body": "');
            $combody[$index] = substr($combody[$index], 0, -2);

            $resultcomment = $comid[$index];

            mysqli_query($conn, "INSERT INTO comments (postId, Id, name, email, body) VALUES ('". $postid[$index] ."','". $comid[$index] ."','". $name[$index] ."','". $email[$index] ."','". $combody[$index] ."')");
        }
        $useridres = count($userid);
        $resultposts = 0;
        for($index = 0; $index < $useridres; $index++){
            $userid[$index] = trim($userid[$index], '"userId": ');
            $userid[$index] = substr($userid[$index], 0 , -2);

            $id[$index] = trim($id[$index], '"id": ');
            $id[$index] = substr($id[$index], 0, -2);

            $title[$index] = trim($title[$index], '"title": "');
            $title[$index] = substr($title[$index], 0, -3);

            $body[$index] = trim($body[$index], '"body": "');
            $body[$index] = substr($body[$index], 0, -2);
            
            $resultposts = $id[$index];

            mysqli_query($conn, "INSERT INTO posts (userId, Id, title, body) VALUES ('". $userid[$index] ."','". $id[$index] ."','". $title[$index] ."','". $body[$index] ."')");
        }

        echo("<script>console.log('Загружено ". $resultposts ." записей и ". $resultcomment ." комментариев');</script>");
        
        echo '<pre>';
        print_r($userid);
        echo '</pre>';


        /*if(isset($_POST['submit'])){ 
            if(isset($_GET['go'])){ 
            if(preg_match("^/[A-Za-z]+/", $_POST['name'])){ 

                $name=$_POST['name'];

                $sqlsearch = "SELECT id, title, body FROM posts WHERE title LIKE '%". $name ."%' OR body LIKE '%". $name ."%'";

                $resultser=mysqli_query($conn, $sqlsearch);

                while($row=mysqli_fetch_array($result)){
                    $titlesear = $row['title'];

                    $bodysear = $row['body'];
                    
                    $ID=$row['id'];
                    echo "<ul>n"; 
                    echo "<li>" . "<a  href='"index.php?id=$ID"'>"   .$titlesear . " " . $bodysear .  "</a></li>n"; 
                    echo "</ul>";
                }
            }
            else{ 
                echo  "<p>Пожалуйста, введите поисковый запрос</p>";
                } 
            }
        }
        if(isset($_GET['by'])){ 
            $letter=$_GET['by']; 
            
            $sql="SELECT  id, title, body FROM posts WHERE title LIKE '%" . $letter . "%' OR body LIKE '%" . $letter ."%'"; 
            
            $result=mysqli_query($conn, $sql); 
            
            $numrows=mysqli_num_rows($result); 
            echo  "<p>" .$numrows . " results found for " . $letter . "</p>"; 
            //-Запуск цикла и сортировка результатов 
            while($row=mysqli_fetch_array($result)){ 
            $titles  =$row['title']; 
                        $bodys=$row['body']; 
                        $ID =$row['id'];
            //-Вывести результат в массиве
            echo  "<ul>n"; 
            echo  "<li>" . "<a  href="index.php?id=$ID">"   .$FirstName . " " . $LastName .  "</a></li>n"; 
            echo  "</ul>"; 
            } 
            }*/
    ?>
</body>
</html>