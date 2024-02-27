<link rel="stylesheet" href="css/materialize.css">
<link rel="stylesheet" href="css/materialize.min.css">
<link rel="stylesheet" href="css/ghpages-materialize.css">
<link href="css/bootstrap.min.css" rel="stylesheet" integrity="" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.css">

<script type="text/javascript" src="js/datatables.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/materialize.min.js"></script>
<script src="js/jquery.timeago.min.js"></script>
<script src="js/lunr.min.js"></script>
<script src="js/prism.js"></script>
<script src="js/search.js"></script>
<script src="js/init.js"></script>
<link rel="stylesheet" href="css/uikit.min.css" />
<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>
<script>
    $(document).ready(function(){
        //http://localhost/new_site/index.php
        $(".dropdown-trigger").dropdown();
    });
</script>
<style>
    nav{
        background-color: #fff !important;

    }
    nav ul a{color:#54bc9c !important;}
    .nav-wrapper{border-bottom: 1px solid #7494a4}
    .maiin{background-color:#54bc9c !important;border-bottom: 2px solid #fff; width: 100%}
    .nav-content ul a{color:#fff !important;}

    .sub_badge{background-color: #0b8278}
    .bg-info{color: white !important; background-color: #54bf99 !important;}
    .col-md-5>.uk-badge{padding: 5px !important;}
</style>

<nav class="nav-extended">
    <div class="nav-wrapper">
        <a href="#" class="brand-logo"><img src="images/Med%20ClaimAssist%20Logo_1000px.png" width="200" height="auto"/></a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>

        <ul id="nav-mobile" class="right hide-on-med-and-down">

            <li><a href="sass.html">Reports Dashboard</a></li>
            <li><a href="sass.html">Add Claim</a></li>
            <li><a href="sass.html">Add Doctors</a></li>
            <li><a href="sass.html">Consent Forms</a></li>
            <li><a href="badges.html">ICD10 Lookup</a></li>
            <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">Search<i class="material-icons right">arrow_drop_down</i></a></li>
            <li><a class="dropdown-trigger" href="#!" data-target="dropdown1"><i class="material-icons right">account_circle</i></a></li>
        </ul>
    </div>
    <div class="maiin">
        <div class="nav-content">
            <ul class="tabs tabs-transparent" style="width: auto">
                <li class="tab"><a href="test1">Leads <span class="uk-badge sub_badge">3</span></a></li>
                <li class="tab"><a class="active" href="#test2">Clinical <span class="uk-badge sub_badge">0</span></a></li>
                <li class="tab disabled"><a href="#test3">QA <span class="uk-badge sub_badge">44</span></a></li>
                <li class="tab"><a href="#test4">Pre-Assessment <span class="uk-badge sub_badge">11</span></a></li>
                <li class="tab"><a href="#test4">Open Claims <span class="uk-badge sub_badge">19</span></a></li>
                <li class="tab"><a href="#test4">New Claims <span class="uk-badge sub_badge">15</span></a></li>
            </ul>
        </div>

    </div>
</nav>

<ul class="sidenav" id="mobile-demo">
    <li><a href="sass.html">Reports Dashboard</a></li>
    <li><a href="sass.html">Add Claim</a></li>
    <li><a href="sass.html">Add Doctors</a></li>
    <li><a href="sass.html">Open Claims</a></li>
    <li><a href="sass.html">Consent Forms</a></li>
    <li><a href="badges.html">ICD10 Lookup</a></li>
    <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">Search<i class="material-icons right">arrow_drop_down</i></a></li>
</ul>
<ul id="dropdown1" class="dropdown-content">
    <li><a href="#!">one</a></li>
    <li><a href="#!">two</a></li>
    <li class="divider"></li>
    <li><a href="#!">three</a></li>
</ul>

<div class="uk-placeholder" style="background-color: white">
    <div class="uk-grid uk-animation-bottom-left">
        <div class="col-md-1">Scheme_Savings<hr><span class="badge rounded-pill bg-info text-dark">916,394</span></div>
        <div class="col-md-1">Discount_Savings<hr><span class="badge rounded-pill bg-info text-dark">318,026</span></div>
        <div class="col-md-1">Total_Savings<hr><span class="badge rounded-pill bg-info text-dark">1,234,420</span></div>
        <div class="col-md-1">Average_Days<hr><span class="badge rounded-pill bg-info text-dark">9</span></div>
        <div class="col-md-1">Closed_Cases<hr><span class="badge rounded-pill bg-info text-dark">742</span></div>
        <div class="col-md-1">Claims_Total<hr><span class="badge rounded-pill bg-info text-dark">699</span></div>
        <div class="col-md-1"></div>
        <div class="col-md-5" style="border-left: 1px solid #54bf99;">
            <span>Priority Order <span uk-icon="arrow-right"></span></span>
            <span class="uk-badge purple">2 / 230</span>
            <span class="uk-badge red">2 / 230</span>
            <span class="uk-badge">21 - Members(s)</span>
            <span class="uk-badge">0 - Files</span>
            <span class="uk-badge">2 - Leads</span>
            <span class="uk-badge">2 - Zero Amts</span>
            <span class="uk-badge orange">2 / 230</span>

        </div>


    </div>

</div>
<div class="row" style="border: 1px solid #54bf99; width: 95%; margin-left: auto;margin-right: auto; position: relative;padding: 10px">
    <div class="col-md-8">
        <table id="example" class="striped" style="width:100%">
            <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Claim Number</th>
                <th>SLA / Open Days</th>
                <th>Date / Notes</th>
                <th>Owner</th>
                <th>Client</th>
                <th>PMB?</th>
            </tr>
            </thead>
            <tbody>
            <tr class="uk-text-normal" style="color: purple">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#modal-container" uk-icon="icon: commenting" uk-toggle></a></li></ul></td>
                <td>Tiger Nixon</td>

                <td>90735</td>
                <td>04 / 90</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Admed</td>
                <td><a href="" class="" uk-icon="close"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: purple">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#modal-container" uk-icon="icon: commenting" uk-toggle></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>GAP909909 / 12</td>
                <td>09 / 78</td>
                <td>2022-04-15 13:33:06</td>
                <td>Keasha</td>
                <td>Kaelo</td>
                <td><a href="" class="" uk-icon="check"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: purple">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#modal-container" uk-icon="icon: commenting" uk-toggle></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>GAP4446464 / 9</td>
                <td>23 / 12</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Kaelo</td>
                <td><a href="" class="" uk-icon="close"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>8957565766</td>
                <td>90 / 89</td>
                <td>2022-04-15 13:33:06</td>
                <td>Wanda</td>
                <td>Sanlam</td>
                <td><a href="" class="" uk-icon="close"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>987657676</td>
                <td>09 / 04</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Zestlife</td>
                <td><a href="" class="" uk-icon="check"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>GAP988877 / 09</td>
                <td>04 / 09</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Sanlam</td>
                <td><a href="" class="" uk-icon="close"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>76876868686</td>
                <td>07 / 09</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Medway</td>
                <td><a href="" class="" uk-icon="check"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>MED7876665 / 09</td>
                <td>09 / 11</td>
                <td>2022-04-15 13:33:06</td>
                <td>Shirley</td>
                <td>Western</td>
                <td><a href="" class="" uk-icon="check"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>
                <td>8867688686</td>
                <td>06 / 90</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Medway</td>
                <td><a href="" class="" uk-icon="check"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>

                <td>90735</td>
                <td>04 / 90</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Admed</td>
                <td><a href="" class="" uk-icon="close"></a></td>
            </tr>
            <tr class="uk-text-normal" style="color: red">
                <td><ul class="uk-iconnav">
                        <li><a href="#" uk-icon="icon: link"></a></li>
                        <li><a href="#" uk-icon="icon: pencil"></a></li>
                        <li><a href="#" uk-icon="icon: commenting"></a></li></ul></td>
                <td>Tiger Nixon</td>

                <td>90735</td>
                <td>04 / 90</td>
                <td>2022-04-15 13:33:06</td>
                <td>Stella</td>
                <td>Admed</td>
                <td><a href="" class="" uk-icon="close"></a></td>
            </tr>


            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <div class="uk-card uk-card-default uk-card-body uk-width-1-2@m uk-animation-slide-right uk-animation-slide-right" style="width: 100%">
            <h3 class="uk-card-title"> <u>Priority Claim : <b style="color: #54bf99">[KGP0936579 / 1]</b></u></h3>

            <div class="row">
                <div class="col-md-4">
                    <span uk-icon="user"></span> <b>Tendai Fuma</b>
                </div>
                <div class="col-md-4">
                    <span uk-icon="receiver"></span> <b>0790998765</b>
                </div>
                <div class="col-md-4">
                    <span uk-icon="mail"></span> <b>test@test.com</b>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s11">
                    <textarea id="textarea2" class="materialize-textarea" data-length="120"></textarea>
                    <label for="textarea2">Type a note here</label>

                    <button class="btn waves-effect waves-light" type="submit" name="action" style="border: 1px solid #54bf99"><span uk-icon="check"></span> Save note</button>
                    <button class="btn waves-effect waves-light" type="submit" name="action" style="border: 1px solid #54bf99"><span uk-icon="list"></span> View</button>
                    <button class="btn waves-effect waves-light" type="submit" name="action" style="border: 1px solid #54bf99"><span uk-icon="pencil"></span> Edit</button>
                    <button class="btn waves-effect waves-light pulse" type="submit" name="action" style="border: 1px solid #54bf99"><span uk-icon="chevron-right"></span> Next Claim</button>
                </div>

            </div>
            <div class="uk-grid-medium uk-flex-middle uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-width-expand uk-first-column">

                    <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                        <li><a href="#" id="mytime">2022-04-20 10:35:13</a></li>
                        <li><a href="#">Keasha</a></li>
                        <li><i><a href="#">4 Days Ago</a></i></li>
                    </ul>
                    <div class="uk-comment-body" style="background-color: whitesmoke; padding: 10px; border-radius: 10px">
                        Elective procedure - Voluntary use of a non-dsp / Tonsils. Dr Samson - does not allow discounts. GAP to pay the member please.
                    </div>
                </div>

            </div>
            <div class="uk-grid-medium uk-flex-middle uk-grid uk-grid-stack" uk-grid="">
                <div class="uk-width-expand uk-first-column">

                    <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                        <li><a href="#" id="mytime">2022-04-20 10:35:13</a></li>
                        <li><a href="#">Keasha</a></li>
                        <li><i><a href="#">4 Days Ago</a></i></li>
                    </ul>
                    <div class="uk-comment-body" style="background-color: whitesmoke; padding: 10px; border-radius: 10px">
                        Elective procedure - Voluntary use of a non-dsp / Tonsils. Dr Samson - does not allow discounts. GAP to pay the member please.
                    </div>
                </div>

            </div>


        </div>

    </div>
</div>



<div id="modal-container" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <button class="uk-modal-close-default" type="button" uk-close></button>

        <div class="row uk-card uk-card-default uk-card-body">
            <div class="col-md-12">
                <div class="row" style="background-color: #54bf99; color: #fff">
                    <div class="col-md-4">
                        Claim Number : <b>8988900</b>
                    </div> <div class="col-md-4">
                        Policy Number : <b>8988900</b>
                    </div>
                    <div class="col-md-4">
                        Username : <b>Stella</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        Client Name : <b>Admed</b>
                    </div>
                    <div class="col-md-4">
                        Date Entered : <b><b style="color: red"><u>2022-04-22 09:58:30</u></b></b>
                    </div>
                    <div class="col-md-4">
                        Created By : <b>System</b>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-md-4">
                        Full Name : <b>MICHELLE DONIAN</b>
                    </div>
                    <div class="col-md-4">
                        ID Number : <b>7405070093087</b>
                    </div>
                    <div class="col-md-4">
                        Email : <b></b>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-md-4">
                        Telephone : <b>0215545638</b>
                    </div>
                    <div class="col-md-4">
                        Cell Number : <b>0835461150</b>
                    </div>
                    <div class="col-md-4">
                        Patient(s) : <b>MICHELLE DONIAN</b>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-md-4">
                        Scheme Name : <b>Discovery Health Medical Scheme</b>
                    </div>
                    <div class="col-md-4">
                        Scheme Option : <b>Smart Plan</b>
                    </div>
                    <div class="col-md-4">
                        Member Number : <b>404328092</b>
                    </div>
                </div><hr>
                <div class="uk-margin">
                    <textarea class="uk-textarea" rows="5" placeholder="Type a note here"></textarea>

                </div>
                <p uk-margin>

                    <button class="uk-button uk-button-primary uk-button-small" style="background-color: #54bf99"><span uk-icon="check"></span> Post</button>

                </p>
            </div>
        </div>
    </div>
</div>
<div class="footer-copyright" style="padding-left: 20px">
    © 2022 Medclaim Assist
    <a class="grey-text text-lighten-4 right" href="#!">More Links</a>

</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>