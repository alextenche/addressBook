<?php require_once(__DIR__ .'/core/init.php'); ?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Alexandru Tenche">
    <title>addressBook | Welcome</title>
    <link rel="stylesheet" href="css/foundation.css"/>
    <link rel="stylesheet" href="css/custom.css"/>
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>
<br><br>
<div class="row">
    <div class="large-6 columns">
        <h1> addressBook </h1>
    </div>

    <div class="large-6 columns">
        <a class="add-btn button right small" data-reveal-id="addModal" href="#"> add contact </a>
        <div id="addModal" class="reveal-modal" data-reveal>
            <h2> add contact </h2>
            <form id="addContact" action="#" method="post">

                <div class="row">
                    <div class="large-6 columns">
                        <label> first name
                            <input name="first_name" type="text" placeholder="add first name here (ex: Alex)"/>
                        </label>
                    </div>
                    <div class="large-6 columns">
                        <label> last name
                            <input name="last_name" type="text" placeholder="last name here (ex: Tenche)"/>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-4 columns">
                        <label> email
                            <input name="email" type="email" placeholder="email address (ex: alex.tenche@gmail.com)"/>
                        </label>
                    </div>
                    <div class="large-4 columns">
                        <label> phone
                            <input name="phone" type="text" placeholder="add phone number here"/>
                        </label>
                    </div>
                    <div class="large-4 columns">
                        <label>Contact Group
                            <select name="contact_group">
                                <option value="family"> family </option>
                                <option value="friends"> friends </option>
                                <option value="business"> business </option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-6 columns">
                        <label> first address
                            <input name="address1" type="text" placeholder="street and number (ex: Anystreet, nr 10)"/>
                        </label>
                    </div>
                    <div class="large-6 columns">
                        <label> second address
                            <input name="address2" type="text" placeholder="street and number (ex: Intothewild, nr 2)"/>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-4 columns">
                        <label> city
                            <input name="city" type="text" placeholder="enter city (ex: Timisoara)"/>
                        </label>
                    </div>
                    <div class="large-4 columns">
                        <label> state
                            <select name="state">
                                <?php foreach ($states as $stateCode => $stateName) : ?>
                                    <option value="<?php echo $stateCode; ?>"><?php echo $stateName; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <div class="large-4 columns">
                        <label>Cod Postal<input name="zipcode" type="text"
                                                placeholder="introduceti codul postal"/></label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 columns">
                        <label> notes
                            <textarea name="notes" placeholder="any notes of info"></textarea>
                        </label>
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
