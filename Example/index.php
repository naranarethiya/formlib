<?php 
    require_once('../Form.php');
    $form_obj=new Form();
    $form_model=array();

    /* To see working of the form use set_data variable in $_GET method 
    and $form_model variable will get filled in form */

    if(isset($_GET['set_data']) && $_GET['set_data']=='1') {
        /* Form model array for filling data 
            Make sure that array key name and field name should be same
        */
        $form_model=array(
            'first_name'=>'Naran',
            'last_name'=>'Arethiya',
            'gender'=>'male',
            'company'=>'Company Pvt Ltd',
            'email'=>'naranarethiya@gmail.com',
            'phone'=>'8879331245',
            'website'=>'http://demowebsite.com',
            'about_you'=>'This is my about',
            'sports'=>array('Cricket','Table Tennis'),
            'contact-preference'=>'AM',
            'newsletter'=>'1',
            'user'=>array(
                'username'=>'naranarethiya',
                /* Password field will not fill */
                'password'=>'123456'
            )
        );
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>FormLib Core PHP example</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        
        <?php echo $form_obj->form_model($form_model, 'submit.php'); ?>
            <div class="row">
                <div class="col-md-12">
                    <h2>User Registration Us 
                        <small style="float: right">
                            <a href="index.php?set_data=1">Click here Fill Form with form_model</a>
                        </small>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="first">First Name</label>
                        <?php
                            $attribute=array(
                                'class'=>'form-control',
                                'placeholder'=>"First name"
                            );
                            echo $form_obj->form_input('first_name',$attribute);
                        ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="last">Last Name</label>
                        <?php
                            $attribute=array(
                                'class'=>'form-control',
                                'placeholder'=>"Last name"
                            );
                            echo $form_obj->form_input('last_name',$attribute);
                        ?>
                        
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="first">Gender</label>
                        <?php
                            $attribute=array(
                                'class'=>'form-control',
                            );
                            $gender_option=array(
                                ''=>'Select',
                                'male'=>'Male',
                                'female'=>'female',
                            );
                            echo $form_obj->form_dropdown('gender',$gender_option,'',$attribute);
                        ?>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <?php
                            $attribute=array(
                                'class'=>'form-control',
                                'placeholder'=>"Company name"
                            );
                            echo $form_obj->form_input('company',$attribute);
                        ?>
                    </div>
                </div>
                <!--  col-md-6   -->

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <?php
                            $attribute=array(
                                'type'=>'tel',
                                'class'=>'form-control',
                                'placeholder'=>"Phone No."
                            );
                            echo $form_obj->form_input('phone',$attribute);
                        ?>
                    </div>
                </div>
                <!--  col-md-6   -->
            </div>
            <!--  row   -->


            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <?php
                            $attribute=array(
                                'type'=>'email',
                                'class'=>'form-control',
                                'placeholder'=>"Email"
                            );
                            echo $form_obj->form_input('email',$attribute);
                        ?>
                    </div>
                </div>
                <!--  col-md-6   -->

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="url">Your Website <small>Please include http://</small></label>
                        <?php
                            $attribute=array(
                                'type'=>'url',
                                'class'=>'form-control',
                                'placeholder'=>"Website"
                            );
                            echo $form_obj->form_input('website',$attribute);
                        ?>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="bio">About You</label>
                    <?php
                        $attribute=array(
                            'class'=>'form-control',
                            'placeholder'=>"About You"
                        );
                        echo $form_obj->form_textarea('about_you',$attribute);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="Interest">Favorite Sports</label>
                    <div class="checkbox">
                        <label>
                            <?php
                                echo $form_obj->form_checkbox('sports[]','Cricket');
                            ?>
                            Cricket
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <?php
                                echo $form_obj->form_checkbox('sports[]','Football');
                            ?>
                            Football
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <?php
                                echo $form_obj->form_checkbox('sports[]','Hockey');
                            ?>
                            Hockey
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <?php
                                echo $form_obj->form_checkbox('sports[]','Table Tennis');
                            ?>
                            Table Tennis
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <?php
                                echo $form_obj->form_checkbox('sports[]','Volleyball');
                            ?>
                            Volleyball
                        </label>
                    </div>

                </div>
            </div>


            <label for="contact-preference">When is the best time of day to reach you?</label>
            <div class="radio">
                <label>
                    <?php
                        echo $form_obj->form_radio('contact-preference','AM').' Morning';
                    ?>
                </label>
            </div>
            <div class="radio">
                <label>
                    <?php
                        echo $form_obj->form_radio('contact-preference','PM').' Evening';
                    ?>
                </label>
            </div>

            <label for="newsletter">Would you like to receive our email newsletter?</label>
            <div class="checkbox">

                <label>
                    <?php
                        echo $form_obj->form_checkbox('newsletter','1');
                    ?>
                     Sure!
                </label>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3>Login User Details</h3>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Username</label>
                        <?php
                            $attribute=array(
                                'class'=>'form-control',
                                'placeholder'=>"Login Username"
                            );
                            echo $form_obj->form_input('user[username]',$attribute);
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password</label>
                        <?php
                            $attribute=array(
                                'class'=>'form-control',
                                'placeholder'=>"Password"
                            );
                            echo $form_obj->form_password('user[password]',$attribute);
                        ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        <?php echo $form_obj->form_close(); ?>
    </div>
</body>
</html>