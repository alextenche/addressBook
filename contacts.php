<?php include('core/init.php');

// create db object
$db = new Database;

// run query
$db->query("SELECT * FROM contacts");

// assign result set
$contacts = $db->resultset();?>

<div class="row">
    <div class="large-12 columns">
        <table>
        	<thead>
        		<tr>
        			<th width="200">Name</th>
        			<th width="130">Phone</th>
        			<th width="200">Email</th>
    				<th width="250">Address</th>
   					<th width="250">Group</th>
        			<th width="250">Action</th>
        		</tr>
        	</thead>
        	<tbody>

            <?php foreach($contacts as $contact) : ?>

        		<tr>
        			<td><a href="contact.html"><?php echo $contact->first_name.' '.$contact->last_name ;?></a></td>
        			<td><?php echo $contact->phone; ?></td>
    				<td><?php echo $contact->email; ?></td>
       				<td>
        				<ul>
        					<li><?php echo $contact->address1; ?></li>
        					<li><?php if($contact->address2) echo $contact->address2; ?></li>
                            <li><?php echo $contact->city. ' ' . $contact->state.' '.$contact->zipcode; ?></li>
                           
        				</ul>
        			</td>
        			<td><?php echo $contact->contact_group; ?></td>
        			<td>
        				<ul class="button-group radius">
        					<li>
								<a href="#" class="button tiny" data-reveal-id="editModal<?php echo $contact->id; ?>" data-contact-id="<?php echo $contact->id; ?>">Edit</a>
								<div id="editModal<?php echo $contact->id; ?>" data-cid="<?php echo $contact->id; ?>" class="reveal-modal editModal" data-reveal>
								
									<h2>Edit Contact</h2>
									<form id="editContact" action="#" method="post">
										<div class="row">
											<div class="large-6 columns">
											<label>prenume<input name="first_name" type="text" value="<?php echo $contact->first_name; ?>" /></label>
										</div>

										<div class="large-6 columns">
											<label>nume<input name="last_name" type="text" value="<?php echo $contact->last_name; ?>"  /></label>
										  </div>
										</div>

										<div class="row">
										  <div class="large-4 columns">
											<label>email<input name="email" type="email" value="<?php echo $contact->email; ?>"  /></label>
										  </div>

										  <div class="large-4 columns">
											<label>telefon<input name="phone" type="text" value="<?php echo $contact->phone; ?>"  /></label>
										  </div>

										  <div class="large-4 columns">
											<label>Contact Group
												<select name="contact_group">
													<option value="Family" <?php if($contact->contact_group == 'Family'){ echo 'selected'; }?>>Family</option>
													<option value="Friends" <?php if($contact->contact_group == 'Friends'){ echo 'selected'; }?>>Friends</option>
													<option value="Business" <?php if($contact->contact_group == 'Business'){ echo 'selected'; }?>>Business</option>
												</select>
											</label>
										  </div>
										</div>

										<div class="row">
										  <div class="large-6 columns">
											<label>prima adresă<input name="address1" type="text" value="<?php echo $contact->address1; ?>" /></label>
										  </div>

										  <div class="large-6 columns">
											<label>Address 2<input name="address2" type="text" value="<?php echo $contact->address2; ?>" /></label>
										  </div>
										</div>

										<div class="row">
										  <div class="large-4 columns">
											<label>Orasul<input name="city" type="text" value="<?php echo $contact->city; ?>" /></label>
										  </div>

										<div class="large-4 columns">
											<label>Judet
												<select name="state">
													<?php foreach($states as $key => $value) :?>
														<?php if($key == $contact->state){
															$selected='selected';
														} else {
															$selected='';
														}?>
														<option value="<?php echo $key;?>" <?php echo $selected; ?>><?php echo $value;?></option>
													<?php endforeach; ?>
												</select>
											</label>
										  </div>

										  <div class="large-4 columns">
											<label>Cod Postal<input name="zipcode" type="text" value="<?php echo $contact->zipcode; ?>" /></label>
										  </div>
										</div>

										<div class="row">
										  <div class="large-12 columns">
											<label>Notite<textarea name="notes" ><?php echo $contact->notes; ?></textarea></label>
										  </div>
										 </div>
										 <input name="id" type="hidden" value="<?php echo $contact->id; ?>">
										 <input name="submit" type="submit" class="add-btn button right small" value="Submit">
										 <a class="close-reveal-modal" href="">&#215;</a>
									  </form>
								
								
								
								
								
								</div>
							</li>
        					<li><a href="#" class="button tiny alert">Delete</a></li>
        				</ul>
        			</td>
        		</tr>

            <?php endforeach; ?>

        	</tbody>
		</table>
    </div>
</div>