            <?php if ($return->status == 'In Progress' || empty($user) ||  empty($return->warehouses)) { ?>


            <div class="row">

                 <div  class="col-lg-12 col-xs-12 col-sm-12">
                    
                    <h2 class="">
                    Please Complete Your Company Details Before Use It !.
                    </h2>
                 </div>

            </div>
            <br>
            <hr>



            <div class="row">

                <?php if ($return->status == 'In Progress') { ?>
                <div  class="col-lg-4 col-xs-12 col-sm-12" id="div-company-pulsate">


                    <p class="bold">Please Complete Your Company Information</p>
                    <span>
                        Click On The Right Top On The Page That Have Your Account Name, And Click "Manage Company"
                        Or <?= Html::a('Click Here', ['/company/manage-company', 'company_id' => (string)$company->company], ['class' => 'bold']) ?> It Will Direct To "Manage Company" Page.
                    </span>
                    <hr>
                    <span>
                        <h4>Tutorial</h4>
                        <br>

                    </span>

                </div>
                <?php } else { ?>
                <div  class="col-lg-4 col-xs-12 col-sm-12" >

                    <div class="card-icon">
                        <i class="fa fa-check font-green-haze theme-font"></i>
                    </div>

                    <p class="bold">Complete</p>
                    <span>
                        Click On The Right Top On The Page That Have Your Account Name, And Click "Manage Company"
                        Or <?= Html::a('Click Here', ['/company/manage-company', 'company_id' => (string)$company->company], ['class' => 'bold']) ?> It Will Direct To "Manage Company" Page.
                    </span>
                    <hr>
                    <span>
                        <h4>Tutorial</h4>
                        <br>

                    </span>

                </div>





                <?php } ?>


                <?php if (empty($return->warehouses)) { ?>
                <div  class="col-lg-4 col-xs-12 col-sm-12" id="div-warehouse-pulsate">
                    <p class="bold">Please Complete Your Company WareHouse</p>
                    <span>
                        Click On The Right Top On The Page That Have Your Account Name, And Click "Manage WareHouse"
                        Or <?= Html::a('Click Here', ['/company/manage-warehouse', 'company_id' => (string)$company->company], ['class' => 'bold']) ?> It Will Direct To "Manage WareHouse" Page.
                    </span>
                    <hr>
                    <span>
                        <h4>Tutorial</h4>
                        <br>

                    </span>

                </div>
                <?php } else { ?>
                <div  class="col-lg-4 col-xs-12 col-sm-12" >


                    <div class="card-icon">
                        <i class="fa fa-check font-green-haze theme-font"></i>
                    </div>


                    <p class="bold">Complete</p>
                    <span>
                        Click On The Right Top On The Page That Have Your Account Name, And Click "Manage WareHouse"
                        Or <?= Html::a('Click Here', ['/company/manage-warehouse', 'company_id' => (string)$company->company], ['class' => 'bold']) ?> It Will Direct To "Manage WareHouse" Page.
                    </span>
                    <hr>
                    <span>
                        <h4>Tutorial</h4>
                        <br>

                    </span>

                </div>
               
                <?php } ?>



                <?php if (empty($user)) { ?>
                <div  class="col-lg-4 col-xs-12 col-sm-12" id="div-user-pulsate">
                    <p class="bold">Please Add Your User In Company</p>
                    <span>
                        Click On The Right Top On The Page That Have Your Account Name, And Click "List User"
                        Or <?= Html::a('Click Here', ['/user/list-user', 'company_id' => (string)$company->company], ['class' => 'bold']) ?> It Will Direct To "List User" Page.
                    </span>
                    <hr>
                    <span>
                        <h4>Tutorial</h4>
                        <br>

                    </span>

                </div>
                <?php } else { ?>
                <div  class="col-lg-4 col-xs-12 col-sm-12" >

                    <div class="card-icon">
                        <i class="fa fa-check font-green-haze theme-font"></i>
                    </div>



                    <p class="bold">Complete</p>
                    <span>
                        Click On The Right Top On The Page That Have Your Account Name, And Click "List User"
                        Or <?= Html::a('Click Here', ['/user/list-user', 'company_id' => (string)$company->company], ['class' => 'bold']) ?> It Will Direct To "List User" Page.
                    </span>
                    <hr>
                    <span>
                        <h4>Tutorial</h4>
                        <br>

                    </span>

                </div>
               
                <?php } ?>




            </div>



            <?php } else { ?>





            <?php } ?>