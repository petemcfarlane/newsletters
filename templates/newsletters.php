<?php 
$query 		 = OC_DB::prepare('SELECT id, subject FROM *PREFIX*newsletters ORDER BY created DESC');
$result		 = $query->execute();
$newsletters = $result->fetchAll();

if ( isset ( $_GET['id'] ) && is_numeric($_GET['id']) ) {
	$query  = OC_DB::prepare('SELECT * FROM *PREFIX*newsletters WHERE id = ?');
	$result = $query->execute( array ( $_GET['id'] ) );
	$news   = $result->fetchRow();
} elseif ( isset($_['id']) && is_numeric($_['id']) ) {
	$query  = OC_DB::prepare('SELECT * FROM *PREFIX*newsletters WHERE id = ?');
	$result = $query->execute( array ( $_['id'] ) );
	$news   = $result->fetchRow();
}


$query = OC_DB::prepare('SELECT * FROM *PREFIX*member_lists');
$result = $query->execute();
$member_lists = $result->fetchAll();
$lists = array();
foreach ($member_lists as $member_list) {
	$lists[$member_list['memberlist_id']] = $member_list['memberlist_name'];
}?>

<div id="groups_header">
	<a href="?app=newsletters" class="tag" id="create_newsletter">New</a>
	<h1>Newsletters</h1>
</div>


<div id="leftcontent">
	<?php foreach ($newsletters as $newsletter) { ?>
		<a class="newsletter_link<?php if ($news) { if ( $news['id'] === $newsletter['id']) print ' active'; } ?>" id="newsletter_id_<?php print $newsletter['id']; ?>" href="?app=newsletters&id=<?php print $newsletter['id']; ?>">
			<?php print $newsletter['subject'];?>
		</a>
	<?php } ?>
</div><!-- end of #left_content -->


<div id="newsletters_header">
	<button id="edit_view">Edit</button>
	<button id="text_view">Text preview</button>
	<button id="html_view">HTML preview</button>
	<button id="sending_view">Sending</button>
</div>


<div id="rightcontent">
	
	<?php if ( $news ) { ?>
		
		<form id="newsletter_settings" class="viewing" enctype="multipart/form-data">
			<input id="newsletter_id" type="hidden" name="id" value="<?php print $news['id']; ?>" />
			<p>
				<label for="edit_subject">Subject:</label>
				<input id="edit_subject" class="hidden" name="subject" type="text" maxlength="150" value="<?php print $news['subject']; ?>" />
				<span id="edit_subject_span"><?php print $news['subject']; ?></span>
			</p>

			<p>
				<label>Member lists:</label> 
				<?php $member_lists = explode(',', $news['member_lists'], -1); ?>
				<select class="member_lists" title="+ Member Lists" multiple="multiple" data-id="<?php print $news['id']; ?>">
					<?php foreach ($lists as $list => $name) {
						$selected = in_array($name,$member_lists) ? ' selected="selected"' : '';
						print "<option$selected>$name</option>";
					} ?>
				</select>
			</p>
			<p><label>Created on:</label> <span><?php print $news['created']; ?></span> <label>by </label> <span><?php print $news['author']; ?></span></p>
			<p<?php print ($news['modifier'] !== NULL) ? '' : ' class="hidden"'; ?>><label>Last Modified on:</label> <span id="modified"><?php print $news['modified']; ?></span> <label>by </label> <span id="modifier"><?php print $news['modifier']; ?></span></p>
			<p><label>Newsletter URL:</label> <a target="_blank" href="http://www.sontia.com/newsletters/?id=<?php print $news['id']; ?>">http://www.sontia.com/newsletters/?id=<?php print $news['id']; ?></a></p>
				
		<?php $content = json_decode($news['content'], true);
		foreach ($content as $key => $story) { ?>
			<fieldset id="story_<?php print $key; ?>">
			<p>
				<label for="heading_<?php print $key; ?>">Heading</label>
				<input id="heading_<?php print $key; ?>" name="story[<?php print $key; ?>][heading]" type="text" value="<?php print $story['heading']; ?>" />
			</p>
			<p>
				<label for="text_<?php print $key; ?>">Text</label>
				<textarea id="text_<?php print $key; ?>" name="story[<?php print $key; ?>][text]" ><?php print $story['text']; ?></textarea>
			</p>
			<p>
				<label for="link_<?php print $key; ?>">Link</label>
				<input id="link_<?php print $key; ?>" name="story[<?php print $key; ?>][link]" type="text" value="<?php print $story['link']; ?>" />
			</p>
			
			<p<?php print $story['image']['encoded'] ? '' : ' class="hidden"'; ?>>
				<label for="image_<?php print $key; ?>">Image</label>
				<img id="view_image_<?php print $key; ?>"src="data:image/jpeg;base64,<?php print $story['image']['encoded']; ?>" alt="<?php print $story['image']['name']; ?>" />
			</p>
			<p>
				<label for="image_<?php print $key; ?>_title">Image title</label>
				<input id="image_<?php print $key; ?>_title" name="story[<?php print $key; ?>][image][name]" type="text" value="<?php print $story['image']['name']; ?>" />
			</p>
			<p>
				<label for="image_<?php print $key; ?>">Upload image</label>
				<input type="file" id="image_<?php print $key; ?>" name="story[<?php print $key; ?>]" />
			</p>
			</fieldset>
		<?php } ?>
			<p>
				<button id="add_news">Add news</button>
			</p>
		
		</form>

		<div id="text_preview" class="hidden viewing">
			<pre><?php print file_get_contents("https://cloud.sontia.com/newsletter.php?id=$news[id]&view=text"); ?></pre>
		</div><!-- end of #text_preview -->
		
		<iframe id="html_preview" class="hidden viewing" src="/apps/newsletters/templates/part.htmlpreview.php?id=<?php print $news['id']; ?>"></iframe>
		
		<div id="sending_window" class="hidden viewing">
			<p>Do you want to email all members of <strong><?php foreach ($member_lists as $list => $name) print $name.', '; ?></strong> the newsletter "<strong><?php print $news['subject']; ?></strong>"?</p>
			<form id="queue_mail">
				<input type="hidden" name="newsletter_id" value="<?php print $news['id']; ?>" />
				<input type="submit" value="send"/>
			</form>
		</div><!-- end of #sending_window -->
		
	<?php } else { ?>
		
		<h1>New Newsletter</h1>
	
		<form action="?app=newsletters" method="post" enctype="multipart/form-data">
			<p>
				<label for="subject">Subject</label>
				<input id="subject" name="subject" type="text" />
			</p>
			<fieldset id="story_0">
				<p>
					<label for="heading_0">Headding</label>
					<input id="heading_0" name="story[0][heading]" type="text" />
				</p>
				<p>
					<label for="text_0">Text</label>
					<textarea id="text_0" name="story[0][text]" ></textarea>
				</p>
				<p>
					<label for="link_0">Link</label>
					<input id="link_0" name="story[0][link]" type="text" />
				</p>
				<p>
					<label for="image_0">Image</label>
					<input type="file" id="image_0" name="story[0]" accept="image/*" />
				</p>
			</fieldset>
			<p>
				<button id="add_news">Add news</button>
			</p>
			<p>
				<input type="submit" name="new_newsletter" value="Save" />
			</p>
		</form>
	
	<?php } ?>
</div><!-- end of #right_content -->