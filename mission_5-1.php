<html>
<head>
	<meta charset="utf-8">
	<title>mission5</title>
</head>
<body>
<h2>名前・目玉焼きになにかける？</h2>


<?php
	//echo "接続開始<br>";
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//echo "接続完了<br>";

	//echo "テーブル5の作成<br>";
	$sql = "CREATE TABLE IF NOT EXISTS tabletest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(35),"
	. "comment TEXT,"
	. "time char(20),"
	. "pass char(20)"
	.");";
	$stmt = $pdo->query($sql);
	
	
	//echo "テーブルの確認開始<br>";
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		//echo $row[0];
		//echo '<br>';
	}
	//echo "<hr>";

	//echo "意図した内容のテーブルの作成の確認開始<br>";
	$sql ='SHOW CREATE TABLE tabletest';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		//echo $row[1];
	}//echo "<br>";
	//echo "<hr>";
?>
<?php
//変数
	//出力パラメータ変数
	$Error_number = 0;		//エラー番号
	$CLEAR = 0;			//プログラム成功パラメータ
	//格納配列
	$text_array = array();		//カラム1行の配列データ
	//入力データ
	$input_name;
	$input_comment;
	$input_time;				//投稿時間
	$input_pass;				//パスワード
	//編集時変数
	$check_edit = true;//新規に追加するときtrue 編集するときfalse;
	$edit_number = 0;		//編集データ番号

//出力関数
	//プログラム成功表示関数
	function out_clear($clear){
		if($clear != 0){
			if($clear == "a"){
				echo "<h3>データを書き込みました</h3>";
			}elseif($clear == "b"){
				echo "<h3>データを編集しました</h3>";
			}elseif($clear == "c"){
				echo "<h3>データを削除しました</h3>";
			}elseif($clear == "d"){
				echo "<h3>編集モードになります</h3>";
			}echo "<hr>";
			echo $clear;
		}
	}
	//エラー表示関数
	function out_error($error_number){
		if($error_number != 0){
			echo "<h3>error</h3>";
			echo "error_number = ".$error_number."<> ";
			if($error_number ==11122 || $error_number == 1212 || $error_number ==1312){
				echo "情報が入っていません。";
			}elseif($error_number ==112){	
				echo "名前が未入力です。";
			}elseif($error_number ==113){
				echo "コメントが未入力です。";
			}elseif($error_number ==114){
				echo "パスワードが未入力です。";
			}elseif($error_number ==115){
				echo "コメント・パスワードが未入力です。";
			}elseif($error_number ==116){
				echo "名前・コメントが未入力です。";
			}elseif($error_number ==117){
				echo "名前・パスワードが未入力です。";
			}elseif($error_number ==122){
				echo "削除番号が未入力です。";
			}elseif($error_number ==123 ||$error_number ==133 || $error_number ==114){
				echo "パスワードが未入力です。";
			}elseif($error_number ==132){
				echo "編集番号が未入力です。";
			}elseif($error_number ==118){
				echo "名前・コメント・パスワードを入力してください。";
			}elseif($error_number ==134){
				echo "編集番号・パスワードを入力してください。";
			}elseif($error_number ==124){
				echo "削除番号・パスワードを入力してください。";
			}elseif($error_number ==131112 || $error_number ==121112){
				echo "パスワードが一致しません。";
			}elseif($error_number ==12112){
				echo "削除番号が見つかりません。";
			}elseif($error_number ==13112){
				echo "編集番号が見つかりません。";
			}
			echo "<hr>";
		}
	}

//main***************************************************************
	//データベースの読み取り
	$sql = 'SELECT * FROM tabletest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();		//データ配列

	if(isset($_POST['subA'] )){//スイッチを押したかどうか
		if($_POST['subA'] == "送信する"){//送信スイッチを押すとき	
		//送信フォーム
			$comment_A = isset($_POST['comment']) && $_POST['comment'] !="";
			$name_A = isset($_POST['name']) && $_POST['name'] !="";
			$password_A = isset($_POST['password_text']) && $_POST['password_text'] !="";
			
			if($comment_A && $name_A && $password_A){
				$input_name = $_POST['name'];
				$input_comment = $_POST['comment'];
				$input_time = date('Y/n/t H:i');
				$input_pass = $_POST['password_text'];
				if($_POST['check_edit']){
					//データ書き込み成功
					$CLEAR = "a";
					//mySQLの書き込み
					$sql = $pdo -> prepare("INSERT INTO tabletest (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
					$sql -> bindParam(':name', $name, PDO::PARAM_STR);
					$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
					$sql -> bindParam(':time', $time, PDO::PARAM_STR);
					$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
					$name = $input_name;
					$comment = $input_comment;
					$time = $input_time;
					$pass = $input_pass;
					$sql -> execute();

				}else{
					//mySQLの更新
					$id = $_POST['edit_number'];
					$name = $input_name;
					$comment = $input_comment;
					$time = $input_time;
					$pass = $input_pass;
					$sql = 'update tabletest set name=:name,comment=:comment,time=:time,pass=:pass where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name, PDO::PARAM_STR);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
					$stmt->bindParam(':time', $time, PDO::PARAM_STR);
					$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
					
					if(!empty($results)){
						foreach($results as $row){
							if($row['id'] == $_POST['edit_number']){
								$CLEAR = "b";
								//編集の成功
								
							}else{
								
							}
						}
					}else{
						$Error_number =11122;
						
					}
				}	
			}else if(!$name_A && $password_A && $comment_A){
				$Error_number =112;
			}else if($name_A && $password_A && !$comment_A){
				$Error_number =113;
			}else if($name_A && !$password_A && $comment_A){
				$Error_number =114;
			}else if($name_A && !$password_A && !$comment_A){
				$Error_number =115;
			}else if(!$name_A && $password_A && !$comment_A){
				$Error_number =116;
			}else if(!$name_A && $password_A && !$comment_A){
				$Error_number =117;
			}else{
				$Error_number =118;
			}
			
		}elseif($_POST['subA'] == "削除する"){//削除スイッチを押すとき
		//削除フォーム	
			$delete_No_A = isset($_POST['delete_No']); 
			$password_A = isset($_POST['password_delete']) && $_POST['password_delete'] !="";
			if($delete_No_A && $password_A){
				$delete_No = $_POST['delete_No'];	//取得No
				$input_time = date('Y/n/t H:i');
				$input_pass  = $_POST['password_delete'];
				$marker = 0;				//削除したらfalse;
				//データベース削除
				$id = $delete_No;
				$sql = 'delete from table5 where id=:id';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
					
				if(!empty($results)){
					foreach($results as $row){
						if($row['id'] == $delete_No){//削除番号と一致したとき
							$marker = 1;
							if($row['pass'] == $input_pass){//password一致//c
								$CLEAR = "c";
								$marker = 2;
							}
						}
					}
					if($marker == 0){//削除番号が一致しなかった
						$Error_number =12112;
					}elseif($marker == 1){//パスワードが一致しなかった
						$Error_number =121112;
					}

				}else{
					$Error_number = 1212;
					
				}
			}elseif(!$delete_No_A && $password_A){//
				$Error_number =122;
			}elseif($delete_No_A && !$password_A){//
				$Error_number =123;
			}else{//
				$Error_number =124;
			}
		}elseif($_POST['subA'] == "編集する"){//編集スイッチを押すとき
		//編集フォーム
			$edit_No_A = isset($_POST['edit_No']);
			$password_A = isset($_POST['password_edit']) && $_POST['password_edit'] !="";
			if($edit_No_A && $password_A){//編集番号が入力された時
				$edit_No = $_POST['edit_No'];//取得No
				$input_time = date('Y/n/t H:i');
				$input_pass  = $_POST['password_edit'];
				$marker = 0;
				
				if(!empty($results)){//ファイルの中身が空でないとき
					foreach($results as $row){
						
						if($row['id'] == $edit_No){
							$marker = 1;
							if($row['pass'] == $input_pass){//password一致//d
								$marker = 2;
								$CLEAR = "d";
								$edit_number = $row['id'];
								$input_name = $row['name'];
								$input_comment = $row['comment'];
								$check_edit = false;//編集モードに入ります。
							}else{//password不一致
							}
							
							
						}else{//番号不一致
							
						}
					}
					if($marker == 0){//編集番号が一致しなかった
						$Error_number =13112;
					}elseif($marker == 1){//パスワードが一致しなかった
						$Error_number =131112;
					}
				}else{//ファイルの中身が空だったとき
					$Error_number =1312;
				}
			}elseif(!$delete_No_A && $password_A){
				$Error_number =132;
			}elseif($delete_No_A && !$password_A){
				$Error_number =133;
			}else{
				$Error_number =134;
			}
		}else{//エラーで変なスイッチを押したとき//14
			
		}
	}else{//スイッチを押していないとき//2
		
	}
	
	if($check_edit){//新規投稿true
		$input_name="";
		$input_comment="";
		echo "【新規投稿モード】";
	}else{//編集false
		echo "【".$edit_number."番編集モード】";
	}
?>


<form method="post">
<input type="hidden" name="edit_number" value="<?= $edit_number?>">
<input type="hidden" name="check_edit" value="<?= $check_edit ?>">
名前:<input type="text" name="name"value="<?= $input_name ?>"><br>
コメント:<input type="text" name="comment"value="<?= $input_comment ?>"><br>
パスワード:<input type="password" name="password_text"><br>
<input type="submit" name="subA" value="送信する"><hr>
削除番号:<input type="text" name="delete_No"><br>
パスワード:<input type="password" name="password_delete"><br>
<input type="submit" name="subA"value="削除する"><hr>
編集番号:<input type="text" name="edit_No"><br>
パスワード:<input type="password" name="password_edit"><br>
<input type="submit" name="subA"value="編集する"><hr>
</form>

<?php
	out_clear($CLEAR);
	out_error($Error_number);
	$sql = 'SELECT * FROM tabletest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].',';
		echo $row['pass'].'<br>';
	}echo "<hr>";
	
?>

</body>

</html>
