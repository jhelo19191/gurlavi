<div class="col-sm-10 col-sm-offset-1">
    <div class="login-container">
        <!--<div class="center">-->
        <!--    <h1>-->
        <!--        <i class="ace-icon fa fa-leaf green"></i>-->
        <!--        <span class="red">GLC</span>-->
        <!--        <span class="white" id="id-text2">Application</span>-->
        <!--    </h1>-->
        <!--    <h4 class="blue" id="id-company-text">&copy; Gur Lavi Corp.</h4>-->
        <!--</div>-->
        <?php if (isset($result)) :?>
            <div style="  margin-left: -3px;" class="span12">
                    <?php if ($result == 'fail'): ?>
                            <div class="alert alert-danger">
                                    <strong> <?php echo $message; ?> </strong>
                            </div>
                    <?php else : ?>
                            <div class="alert alert-success">
                            <strong><?php echo $message; ?></strong>
                            </div>
                    <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="space-6"></div>

        <div class="position-relative">

            <div id="signup-box" class="signup-box visible widget-box no-border">
                <div class="widget-body">
                    <div class="widget-main">

                        <div class="space-6"></div>
                        <p> Enter your details to begin: </p>

                        <form action="<?php echo site_url('pages/newUser'); ?>" method="POST">
                            <fieldset>
                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="text" name="account_name" class="form-control" placeholder="Name" />
                                        <i class="ace-icon fa fa-envelope"></i>
                                    </span>
                                </label>

                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="email" class="form-control" name="c_number" placeholder="Contact Number" />
                                        <i class="ace-icon fa fa-phone"></i>
                                    </span>
                                </label>
                            
                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="email" class="form-control" name="email" placeholder="Email" />
                                        <i class="ace-icon fa fa-envelope"></i>
                                    </span>
                                </label>
                                
                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="text" class="form-control" name="position" placeholder="Position" />
                                        <i class="ace-icon fa fa-envelope"></i>
                                    </span>
                                </label>

                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <select class="form-control" name="user_level">
                                            <option value="1">Accounting</option>
                                            <option value="2">PSR</option>
                                            <option value="3">PNS</option>
                                            <option value="4">Sales Admin</option>
                                        </select>
                                        <!--<i class="ace-icon fa fa-envelope"></i>-->
                                    </span>
                                </label>

                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="text" name="username" class="form-control" placeholder="Username" />
                                        <i class="ace-icon fa fa-user"></i>
                                    </span>
                                </label>

                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="password" name="password" class="form-control" placeholder="Password" />
                                        <i class="ace-icon fa fa-lock"></i>
                                    </span>
                                </label>

                                <label class="block clearfix">
                                    <span class="block input-icon input-icon-right">
                                        <input type="password" name="c_password" class="form-control" placeholder="Repeat password" />
                                        <i class="ace-icon fa fa-retweet"></i>
                                    </span>
                                </label>

                                <div class="space-24"></div>

                                <div class="clearfix">
                                    <button type="reset" class="width-30 pull-left btn btn-sm">
                                        <i class="ace-icon fa fa-refresh"></i>
                                        <span class="bigger-110">Reset</span>
                                    </button>

                                    <button type="button" onclick="$('form').submit(); return false;" class="width-65 pull-right btn btn-sm btn-success">
                                        <span class="bigger-110">Register</span>

                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div><!-- /.widget-body -->
            </div><!-- /.signup-box -->
        </div><!-- /.position-relative -->

</div><!-- /.col -->