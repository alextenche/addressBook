<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>addressBook | Welcome</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>

    <div class="row">
      <div class="large-6 columns">
        <h1>addressBook</h1>
      </div>

      <div class="large-6 columns">
        <a class="add-btn button right small" data-reveal-id="myModal" href="#">adaugă un contact</a>
        <div id="myModal" class="reveal-modal" data-reveal>
          <h2>ad Contact</h2>
          <form>

            <div class="row">
              <div class="large-6 columns">
                <label>prenume<input type="text" placeholder="adaugă prenumele" /></label>
              </div>

              <div class="large-6 columns">
                <label>nume<input type="text" placeholder="adaugă numele" /></label>
              </div>
            </div>

            <div class="row">
              <div class="large-4 columns">
                <label>email<input type="email" placeholder="adaugă adresa de email" /></label>
              </div>

              <div class="large-4 columns">
                <label>telefon<input type="text" placeholder="adaugă telefonul" /></label>
              </div>

              <div class="large-4 columns">
                <label>Contact Group
                	<select>
                		<option value="husker">Family</option>
                		<option value="starbuck">Friends</option>
                		<option value="hotdog">Business</option>
                	</select>
                </label>
              </div>
            </div>

            <div class="row">
              <div class="large-6 columns">
                <label>prima adresă<input type="text" placeholder="adăugați prima adresă" /></label>
              </div>

              <div class="large-6 columns">
                <label>Address 2<input type="text" placeholder="Introduceti Adresa 2" /></label>
              </div>
            </div>

            <div class="row">
              <div class="large-4 columns">
                <label>Orasul<input type="text" placeholder="introduceti orasul" /></label>
              </div>

			<div class="large-4 columns">
                <label>Judet
                	<select>
                		<option value="husker">Arad</option>
                		<option value="husker">Timis</option>
                		<option value="starbuck">CarasSeverin</option>
                		<option value="hotdog">Hunedoara</option>
                	</select>
                </label>
              </div>

              <div class="large-4 columns">
                <label>Cod Postal<input type="text" placeholder="introduceti codul postal" /></label>
              </div>
            </div>

            <div class="row">
              <div class="large-12 columns">
                <label>Notite<textarea placeholder="Introduceti notite daca este nevoie"></textarea></label>
              </div>
             </div>
             <input type="submit" class="add-btn button right small" value="Submit">
             <a class="close-reveal-modal" href="">&#215;</a>
          </form>

        </div>
        </div>

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
        				<tr>
        					<td><a href="contact.html">Keanu Reeves</a></td>
        					<td>1234567</td>
        					<td>keanu@gmail.ro</td>
        					<td>
        						<ul>
        							<li>55 Main Street</li>
        							<li>Amensbury, MA 01913</li>
        						</ul>
        					</td>
        					<td>Family</td>
        					<td>
        						<ul class="button-group radius">
        							<li><a href="#" class="button tiny" data-reveal-id="myModal">Edit</a></li>
        							<li><a href="#" class="button tiny [secondary alert success]">Delete</a></li>
        						</ul>
        					</td>
        				</tr>

        				<tr>
        					<td><a href="contact.html">Keanu Reeves2</a></td>
        					<td>1234567</td>
        					<td>keanu@gmail.ro</td>
        					<td>
        						<ul>
        							<li>55 Main Street</li>
        							<li>Amensbury, MA 01913</li>
        						</ul>
        					</td>
        					<td>Family</td>
        					<td>
        						<ul class="button-group radius">
        							<li><a href="#" class="button tiny" data-reveal-id="myModal">Edit</a></li>
        							<li><a href="#" class="button tiny alert">Delete</a></li>
        						</ul>
        					</td>
        				</tr>
        			</tbody>
				</table>
      		</div>
    	</div>
    

    
    <script src="js/vendor/jquery.js"></script>
	<script src="js/script.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
