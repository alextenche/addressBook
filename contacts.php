<?php
include(__DIR__ . '/core/init.php');

$db = new Database;

// run query
$db->query("SELECT * FROM contacts");

// assign result set
$contacts = $db->resultset(); ?>

<div class="row">
    <div class="large-12 columns">
        <table>
            <thead>
            <tr>
                <th width="200"> name</th>
                <th width="130"> phone</th>
                <th width="200"> email</th>
                <th width="250"> address</th>
                <th width="250"> group</th>
                <th width="250"> action</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td><a href="#"><?php echo $contact->first_name . ' ' . $contact->last_name; ?></a></td>
                    <td><?php echo $contact->phone; ?></td>
                    <td><?php echo $contact->email; ?></td>
                    <td>
                        <ul>
                            <li><?php echo $contact->address1; ?></li>
                            <li>
                                <?php if ($contact->address2) :
                                    echo $contact->address2;
                                endif; ?>
                            </li>
                            <li><?php echo $contact->city . ' ' . $contact->state . ' ' . $contact->zipcode; ?></li>
                        </ul>
                    </td>
                    <td><?php echo $contact->contact_group; ?></td>
                    <td>
                        <ul class="button-group radius">
                            <li>
                                <a href="#" class="button tiny" data-reveal-id="editModal<?php echo $contact->id; ?>"
                                   data-contact-id="<?php echo $contact->id; ?>"> edit </a>
                                <div id="editModal<?php echo $contact->id; ?>" data-cid="<?php echo $contact->id; ?>"
                                     class="reveal-modal editModal" data-reveal>
                                    <h2> edit </h2>
                                    <form id="editContact" action="#" method="post">
                                        <div class="row">
                                            <div class="large-6 columns">
                                                <label> first name
                                                    <input name="first_name" type="text"
                                                           value="<?php echo $contact->first_name; ?>"/>
                                                </label>
                                            </div>
                                            <div class="large-6 columns">
                                                <label> last name
                                                    <input name="last_name" type="text"
                                                           value="<?php echo $contact->last_name; ?>"/>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="large-4 columns">
                                                <label> email
                                                    <input name="email" type="email"
                                                           value="<?php echo $contact->email; ?>"/>
                                                </label>
                                            </div>
                                            <div class="large-4 columns">
                                                <label> phone <input name="phone" type="text"
                                                                     value="<?php echo $contact->phone; ?>"/>
                                                </label>
                                            </div>
                                            <div class="large-4 columns">
                                                <label>Contact Group
                                                    <select name="contact_group">
                                                        <option value="family"
                                                            <?php if ($contact->contact_group == 'family') :
                                                                echo 'selected';
                                                            endif; ?>>
                                                            family
                                                        </option>
                                                        <option value="friends"
                                                            <?php if ($contact->contact_group == 'friends') :
                                                                echo 'selected';
                                                            endif; ?>>
                                                            friends
                                                        </option>
                                                        <option value="business"
                                                            <?php if ($contact->contact_group == 'business') :
                                                                echo 'selected';
                                                            endif; ?>>
                                                            business
                                                        </option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="large-6 columns">
                                                <label> first address
                                                    <input name="address1" type="text"
                                                           value="<?php echo $contact->address1; ?>"/>
                                                </label>
                                            </div>
                                            <div class="large-6 columns">
                                                <label> second address
                                                    <input name="address2" type="text"
                                                           value="<?php echo $contact->address2; ?>"/>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="large-4 columns">
                                                <label> city <input name="city" type="text"
                                                                    value="<?php echo $contact->city; ?>"/>
                                                </label>
                                            </div>
                                            <div class="large-4 columns">
                                                <label> state/county/land
                                                    <select name="state">
                                                        <?php foreach ($states as $stateCode => $stateName) : ?>
                                                            <?php if ($stateCode == $contact->state) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            } ?>
                                                            <option value="<?php echo $stateCode; ?>"
                                                                <?php echo $selected; ?>><?php echo $stateName; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </label>
                                            </div>

                                            <div class="large-4 columns">
                                                <label> postal code
                                                    <input name="zipcode" type="text"
                                                           value="<?php echo $contact->zipcode; ?>"/>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="large-12 columns">
                                                <label> notes
                                                    <textarea name="notes"><?php echo $contact->notes; ?></textarea>
                                                </label>
                                            </div>
                                        </div>
                                        <input name="id" type="hidden" value="<?php echo $contact->id; ?>">
                                        <input name="submit" type="submit" class="add-btn button right small"
                                               value="Submit">
                                        <a class="close-reveal-modal" href="">&#215;</a>
                                    </form>

                                </div>
                            </li>
                            <li>
                                <form id="deleteContact" action="#" method="post">
                                    <input name="id" type="hidden" value="<?php echo $contact->id; ?>">
                                    <input type="submit" class="delete-btn button tiny secondary alert" value="Delete"/>
                                </form>
                            </li>
                        </ul>
                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>