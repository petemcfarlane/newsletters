<?php 
Class OC_Newsletters_App {
	
	/*public static function updateSubject($new_subject) {
		
		$updateQuery = OCP\DB::prepare('UPDATE *PREFIX*newsletters SET subject = ?, modifier = ?, modified = ? WHERE id = ?');
		$updateQuery->execute ( array ( $new_subject['subject'], OC_User::getUser(), date("Y-m-d H:i:s"), $new_subject['id'] ) );

		return array("subject" =>$new_subject['subject'], "modifier" => OC_User::getUser(), "modified" => date("Y-m-d H:i:s"));

	}*/
	

	public static function updateNewsletter($data) {
		
		foreach($data as $key => $value) {
			if ($key === 'subject') {
				$update = array('key'=>$key, 'value'=>$value);
			} elseif ($key === 'story') {
				$existing = OCP\DB::prepare('SELECT content FROM *PREFIX*newsletters WHERE id = ?');
				$result = $existing->execute( array( $data['id'] ) );
				$existing = $result->fetchRow();
				$content = json_decode($existing['content'],true);
				foreach ($value as $i => $val) {
					$story = $i;
					foreach($val as $up_key =>$u) {
						if ($up_key === 'image') {
							foreach($u as $x => $y) {
								$content[$i][$up_key][$x] = $y;
							}
						} else {
							$content[$i][$up_key] = $u;
						}
					}
				}
				$update = array( 'key' => 'content', 'value' => json_encode($content) );
			}
		}
		$query = OCP\DB::prepare("UPDATE *PREFIX*newsletters SET $update[key] = ?, modifier = ?, modified = ?  WHERE id = ? ");
		$query->execute( array( $update['value'], OC_User::getUser(), date("Y-m-d H:i:s"), $data['id'] ) );
		return array("modifier" => OC_User::getUser(), "modified" => date("Y-m-d H:i:s"));
	}


	public static function newNewsletter($request) {

		//if newsletter exists with same subject, dont create.
		$query = OC_DB::prepare('SELECT id FROM *PREFIX*newsletters WHERE subject = ?');
		$result = $query->execute( array( $request['subject'] ) );
		$data = $result->fetchRow();
		
		if ( !$data ) {

			$query = OCP\DB::prepare('INSERT INTO *PREFIX*newsletters (subject, content, created, author) VALUES (?, ?, ?, ?)');
			$query->execute( Array( $request['subject'], $request['content'], date("Y-m-d H:i:s"), OC_User::getUser() ) );
	
			$id = OC_DB::insertid();
			return $id;

		}

	}


	public static function toggleMemberList($request) {
		$query = OCP\DB::prepare('SELECT member_lists FROM *PREFIX*newsletters WHERE id = ?');
		$result = $query->execute( array($request['id']) );
		$data = $result->fetchRow();
		if ($data['member_lists'] === '') {
			$member_lists = $request['checked'] . ',';
			$update = OCP\DB::prepare('UPDATE *PREFIX*newsletters SET member_lists = ?, modified = ?, modifier = ? WHERE id = ?');
			$update->execute( array($member_lists, date("Y-m-d H:i:s"), OC_User::getUser(), $request['id'] ) );
			return array("member_lists" =>$member_lists, "modifier" => OC_User::getUser(), "modified" => date("Y-m-d H:i:s"));
		} else {
			$member_lists = explode(',',$data['member_lists'], -1);
			if ( in_array("$request[checked]",$member_lists) ) {
				$member_lists = array_diff($member_lists, array($request['checked']));
			} else {
				$member_lists[] = $request['checked'];
			}
			$member_lists = implode(',', $member_lists);
			$member_lists = $member_lists . ',';
			$update = OCP\DB::prepare('UPDATE *PREFIX*newsletters SET member_lists = ?, modified = ?, modifier = ? WHERE id = ?');
			$update->execute( array($member_lists, date("Y-m-d H:i:s"), OC_User::getUser(), $request['id'] ) );
			return array("member_lists" =>$member_lists, "modifier" => OC_User::getUser(), "modified" => date("Y-m-d H:i:s"));
		}
	}


	public static function queue_mail($newsletter_id) {
		//get member_lists from newsletter_id
		$query = OCP\DB::prepare('SELECT member_lists FROM *PREFIX*newsletters WHERE id = ?');
		$result = $query->execute( array($newsletter_id) );
		$data = $result->fetchRow();
		if ($data['member_lists'] === '') {
			return array('error' => 'No member list selected');
		} else { //explode list into arary
			$member_lists = explode(',',$data['member_lists'], -1);
			$where = "WHERE member_lists LIKE '%". join("$list%' OR member_lists LIKE '%", $member_lists) . "%'";
			//foreach member 
			$query = OCP\DB::prepare("SELECT member_email FROM *PREFIX*members $where ");
			$result = $query->execute();
			$emails = $result->fetchAll();
			foreach ($emails as $i => $email) {
				$query = OCP\DB::prepare("INSERT INTO *PREFIX*newsletters_sending (email, newsletter, queued, queued_by) VALUES (?, ?, ?, ?) ");
				$query->execute( array( $email['member_email'], $newsletter_id, date("Y-m-d H:i:s"), OC_User::getUser() ) );
				//return array( $email['member_email'], $newsletter_id, date("Y-m-d H:i:s"), OC_User::getUser() );
			}
			return $emails;
		}
	}

	
}
?>