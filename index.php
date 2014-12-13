<?php include('core/init.php');?>
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
        <a class="add-btn button right small" data-reveal-id="addModal" href="#">adaugă un contact</a>
        <div id="addModal" class="reveal-modal" data-reveal>
          <h2>Add Contact</h2>
          <form id="addContact" action="#" method="post">

            <div class="row">
              <div class="large-6 columns">
                <label>prenume<input name="first_name" type="text" placeholder="adaugă prenumele" /></label>
              </div>

              <div class="large-6 columns">
                <label>nume<input name="last_name" type="text" placeholder="adaugă numele" /></label>
              </div>
            </div>

            <div class="row">
              <div class="large-4 columns">
                <label>email<input name="email" type="email" placeholder="adaugă adresa de email" /></label>
              </div>

              <div class="large-4 columns">
                <label>telefon<input name="phone" type="text" placeholder="adaugă telefonul" /></label>
              </div>

              <div class="large-4 columns">
                <label>Contact Group
                	<select name="contact_group">
                		<option value="Family">Family</option>
                		<option value="Friends">Friends</option>
                		<option value="Business">Business</option>
                	</select>
                </label>
              </div>
            </div>

            <div class="row">
              <div class="large-6 columns">
                <label>prima adresă<input name="address1" type="text" placeholder="adăugați prima adresă" /></label>
              </div>

              <div class="large-6 columns">
                <label>Address 2<input name="address2" type="text" placeholder="Introduceti Adresa 2" /></label>
              </div>
            </div>

            <div class="row">
              <div class="large-4 columns">
                <label>Orasul<input name="city" type="text" placeholder="introduceti orasul" /></label>
              </div>

			<div class="large-4 columns">
                <label>Judet
                	<select name="state">
						<option>Select State</option>
						<?php foreach($states as $key => $value) :?>
							<option value="<?php echo $key;?>"><?php echo $value;?></option>
						<?php endforeach; ?>
					</select>
                </label>
              </div>

              <div class="large-4 columns">
                <label>Cod Postal<input name="zipcode" type="text" placeholder="introduceti codul postal" /></label>
              </div>
            </div>

            <div class="row">
              <div class="large-12 columns">
                <label>Notite<textarea name="notes" placeholder="Introduceti notite daca este nevoie"></textarea></label>
              </div>
             </div>
             <input name="submit" type="submit" class="add-btn button right small" value="Submit">
             <a class="close-reveal-modal" href="">&#215;</a>
          </form>

        </div>
        </div>

        <!-- loader image -->
        <div id="loaderImage">
          <img src="img/ajax-loader.gif">
        </div>

        <!-- main content -->
        <div id="pageContent"></div>
    

    
      <script src="js/vendor/jquery.js"></script>
	     <script src="js/script.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
