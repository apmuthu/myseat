
				<h3>	 	 				 
					<div class="property-logo" style="background-image: url(../uploads/logo/<?php echo ($row['logo_filename']=='') ? 'logo.png' : $row['logo_filename'];?>);"></div>
					<?php echo $row['name'];?>
				</h3>
				<br class="cl" />
				<br/>
				<div class="property-image" style="background-image: url(../uploads/img/<?php echo ($row['img_filename']=='') ? 'noImage.png' : $row['img_filename'];?>);"></div>
				<br class="cl" />
				<br/><br/>
				<label><?php echo _contact;?></label>
				<p><strong>
					<?php echo $row['contactperson'];?>
				</p></strong>
				<label><?php echo _adress;?></label>
				<p><strong>
					<?php echo $row['street'];?>
				</p></strong>
				<label><?php echo _zip;?></label>
				<p><strong>
					<?php echo $row['zip'];?>
				</p></strong>
				<label><?php echo _city;?></label>
				<p><strong>
					<?php echo $row['city'];?>
				</p></strong>
				<label><?php echo _country;?></label>
				<p><strong>
					<?php echo $countries[$row['country']];?>
				</p></strong>
				<label><?php echo _email;?></label>
				<p><strong>
					<?php echo $row['email'];?>
				</p></strong>
				<label><?php echo _website;?></label>
				<p><strong>
					<?php echo $row['website'];?>
				</p></strong>
				<label><?php echo _phone;?></label>
				<p><strong>		 	 	 	 	 	 	
					<?php echo $row['phone'];?>
				</p></strong>
				<label><?php echo _fax;?></label>	
				<p>	 	 	 	 	 	 	
					<?php echo $row['fax'];?>
				</p>
				<label>Facebook Link</label>	
				<p>	 	 	 	 	 	 	
					<?php echo $row['social_fb'];?>
				</p>
				<label>Twitter Link</label>	
				<p>	 	 	 	 	 	 	
					<?php echo $row['social_tw'];?>
				</p>
				<br/><br/>	 	 	 	 	 	 	 
				<small>				
					<?php if($row['created']){ echo _created." ".humanize($row['created']);}?>
				</small>

					<!-- Google Map Plugin -->
					<div class="google_map" style="margin-top:80px;">
						<div id="map_canvas">
							<img border='0'src="https://maps.google.com/maps/api/staticmap?markers=size:mid|color:red|<?php echo $row['street'];?>,<?php echo $row['zip'];?> <?php echo $row['city'];?>,<?php echo $countries[$row['country']];?>&zoom=15&size=720x270&maptype=roadmap&sensor=false"/>	
						</div>
					</div>
					<!-- /Google Map -->