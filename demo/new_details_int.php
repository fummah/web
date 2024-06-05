
<?php
session_start();
define("access",true);
include ("classes/controls.php");
$control=new controls();

include ("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
body {
    line-height: 1.6;
    color: #333;
    background-color: white !important;
    margin: 0;
}
.user-details {
    background-color: white;
    padding: 13px 3px;
    border-radius: 8px;
    width: 100%;
    max-width: 100%;
    text-align: left;
}

.user-details h2 {
    margin-top: 0;
    color: #d32f2f;
    font-weight: 700;
    text-align: center;
}

.details-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 10px;
    background-color: #f7f7f9;
}

.detail {
    margin-bottom: 0px;
    border: none;
    flex: 1 1 23%;
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-radius: 4px;
    background-color: white;
    margin: 4px;
    box-sizing: border-box;
    transition: transform 0.5s ease, box-shadow 0.5s ease;

}

.detail label {
    font-size: .8rem;
    color: #9e9e9e;
    flex: 0 0 45%;
}

.detail span {
    font-weight: 500;
    color: #555;
    flex: 1;
    text-align: right;
}
.uk-grid-small label{
    font-weight: 500;
    color: #555;
    font-size: 0.3rem; 
    color:#54bf99;
}
.doc-text{
    font-weight: 500;
    font-size: 0.8rem; 
    color:#555;
}
.detail label, .detail span {
    font-size: 0.9rem;
}
.detail:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.main {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.main ul {
    display: flex;
}

.main ul li {
    list-style: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 40px;
}

.main ul li .icons {
    font-size: 25px;
    color: #54bc9c;
    margin: 0 60px;
}

.main ul li .label {
    font-family: sans-serif;
    letter-spacing: 1px;
    font-size: 12px;
    font-weight: bold;
    color: #54bc9c;
}
*+p {
    margin-top: 0px !important; 
}

.main ul li .step {
    height: 30px;
    width: 30px;
    border-radius: 50%;
    background-color: #d7d7c3;
    margin: 12px 0 10px;
    display: grid;
    place-items: center;
    color: ghostwhite;
    position: relative;
    cursor: pointer;
}

.step::after {
    content: "";
    position: absolute;
    width: 197px;
    height: 3px;
    background-color: #d7d7c3;
    right: 30px;
}

.first::after {
    width: 0;
    height: 0;
}

.main ul li .step .awesome {
    display: none;
}

.main ul li .step p {
    font-size: 18px;
}

.main ul li .active {
    background-color: #54bc9c;
}

.main ul li .active::after {
    background-color: #54bc9c;

}

.main ul li .active p {
    display: none;
}

.main ul li .active .awesome {
    display: flex;
}
.main ul{
    margin: 0 0 2px 0 !important;
}

    .mymain {
        display: flex;
    }


    
.table-wrapper{
    box-shadow: 0px 35px 50px rgba( 0, 0, 0, 0 );
}

.fl-table {
    border-radius: 5px;
    font-size: 12px;
    font-weight: normal;
    border: none;
    border-collapse: collapse;
    width: 100%;
    max-width: 100%;
    white-space: nowrap;
    background-color: white;
}

.fl-table td, .fl-table th {
    text-align: center;
    padding: 8px;
}

.fl-table td {
    border-right: 1px solid #f8f8f8;
    font-size: 12px;
}

.fl-table thead th {
    color: #ffffff;
    background: #4FC3A1;
}


.fl-table thead th:nth-child(odd) {
    color: #ffffff;
    background: #4FC3A1;
}

.fl-table tr:nth-child(even) {
    background: #F8F8F8;
}

/* Responsive */

@media (max-width: 767px) {
    .fl-table {
        display: block;
        width: 100%;
    }
    .table-wrapper:before{
        content: "Scroll horizontally >";
        display: block;
        text-align: right;
        font-size: 11px;
        color: white;
        padding: 0 0 10px;
    }
    .fl-table thead, .fl-table tbody, .fl-table thead th {
        display: block;
    }
    .fl-table thead th:last-child{
        border-bottom: none;
    }
    .fl-table thead {
        float: left;
    }
    .fl-table tbody {
        width: auto;
        position: relative;
        overflow-x: auto;
    }
    .fl-table td, .fl-table th {
        padding: 20px .625em .625em .625em;
        height: 60px;
        vertical-align: middle;
        box-sizing: border-box;
        overflow-x: hidden;
        overflow-y: auto;
        width: 120px;
        font-size: 13px;
        text-overflow: ellipsis;
    }
    .fl-table thead th {
        text-align: left;
        border-bottom: 1px solid #f7f7f9;
    }
    .fl-table tbody tr {
        display: table-cell;
    }
    .fl-table tbody tr:nth-child(odd) {
        background: none;
    }
    .fl-table tr:nth-child(even) {
        background: transparent;
    }
    .fl-table tr td:nth-child(odd) {
        background: #F8F8F8;
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tr td:nth-child(even) {
        border-right: 1px solid #E6E4E4;
    }
    .fl-table tbody td {
        display: block;
        text-align: center;
    }
}
.checkbox-list {
  list-style-type: none;
  padding: 0;
}

.checkbox-list li {
  display: flex;
  align-items: center;
  margin-top: 12px;
}

.checkbox-list input[type="checkbox"] {
  display: none; /* Hide the checkbox */
}

.checkbox-list input[type="checkbox"] + label {
  cursor: pointer;
  transition: color 0.2s;
}

.checkbox-list input[type="checkbox"]:checked + label {
  text-decoration: line-through; /* Line-through text when checkbox is checked */
  color: gray; /* Optional: Change text color when checked */
}
label .uk-badge{
    background-color: #54bf99 !important;
}
td .uk-badge{
    background-color: #54bf99 !important;
}
.pmb-badge{
    color: #54bf99; 
}
.sticky-button {
    position: fixed;
    right: 0;
    top: 40%;
    transform: translateY(-50%);
    background-color: #54bf99;
    color: white;
    border: none;
    padding: 5px 0px;
    border-radius: 10px 0px 0px 10px;    
    cursor: pointer;
    z-index: 100;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s, box-shadow 0.3s;
}

.sticky-button:hover {
    background-color: #333;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    font-size: 14px;
    padding: 10px;
    transition: 0.4s;
}
.sticky-button:hover::after {
 content: "Process Flow";
 transition: 0.4s;
}
.process{
    display: none;
}
.uk-icon-button {
    width: 26px !important;
    height: 26px !important;
}
    </style>
</head>
<body>
<span class="sticky-button"><span uk-icon="more-vertical"></span></span>
    <div class="user-details">
<div class="mymain">
<div style="width:50%; padding:10px; border-right:dashed #54bc9c 1px; border-radius:10px" class="process">
<ul class="checkbox-list">
    <li style="display:block">
      <input type="checkbox" id="item1">
      <label for="item1" title="- Does the account reflect: Medical aid details. Provider practice number, service date, tariff codes, billed amounts, proof of payment (if applicable)
"><span><span class="uk-icon-button" uk-icon="check"></span></span> Was the detailed account received?</label>
<div>
            <label><input class="uk-radio" type="radio" name="radio2"> <span>Yes</span></label>
            <label><input class="uk-radio" type="radio" name="radio2"> <span>No</span></label>
        </div>
    </li>
    <li>
      <input type="checkbox" id="item2">
      <label for="item2" title="- Take down / Note the reference number for the submission and the expected TAT for processing">
      <span><span class="uk-icon-button" uk-icon="check"></span></span> If yes, then submit to the Medical Aid</label>
    </li>

    <li>
      <input type="checkbox" id="item4">
      <label for="item4"><span><span class="uk-icon-button" uk-icon="check"></span></span> Follow up when the processing TAT has lapsed</label>
    </li>
    <li>
      <input type="checkbox" id="item5">
      <label for="item5"><span><span class="uk-icon-button" uk-icon="check"></span></span> Request a copy of the medical statement / notification (if not received)</label>
    </li>
    <li>
      <input type="checkbox" id="item6">
      <label for="item6"><span><span class="uk-icon-button" uk-icon="check"></span></span> Update the Member and share a copy of the medical statement with the Member.</label>
    </li>
  
  </ul>
        </div>

        <div class="details-container">
        <div class="main process">

<ul>
    <li>
        <i class="icons awesome fa-solid fa-hourglass-start"></i>
        <div class="step first">
            <p>1</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Claim Submission</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-book"></i>
        <div class="step second">
            <p>2</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Gap Submission</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-certificate"></i>
        <div class="step third">
            <p>3</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Validation</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-person-circle-check"></i>
        <div class="step fourth">
            <p>4</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Approval</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-thumbs-up"></i>
        <div class="step fifth">
            <p>5</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Payment</p>
    </li>
    <li>
        <i class="icons awesome fa-solid fa-power-off"></i>
        <div class="step sixth">
            <p>6</p>
            <i class="awesome fa-solid fa-check"></i>
        </div>
        <p class="label">Finish</p>
    </li>
</ul>
</div>
        <div class="detail">
                <label for="claim-number">Claim</label>
              <span id="claim-number">[S51.0] PMB, Non-Emergency</span>
            </div>
         
            <div class="detail">
                <label for="date-opened-closed">Date Opened/Closed:</label>
                <span id="date-opened-closed">2023-12-12 08:20:09 / 2023-12-12 13:22:07</span>
            </div>
            <div class="detail">
                <label for="incident-date">Incident Date:</label>
                <span id="incident-date">2023-08-25 To 2023-08-25</span>
            </div>

            <div class="detail">
                <label for="member-number">User</label>
                <span id="member-number">Tendai Fuma</span>
            </div>
           <div class="detail">
                <label for="claim-number">Claim Number:</label>
                <span id="claim-number">GAP3098397 / 1</span>
            </div>
            <div class="detail">
                <label for="policy-number">Policy Number:</label>
                <span id="policy-number">GAP3098397</span>
            </div>

            <div class="detail">
                <label for="client-name">Client Name:</label>
                <span id="client-name">Zestlife</span>
            </div>
            <div class="detail">
                <label for="created-by">Created By:</label>
                <span id="created-by">zeterryj</span>
            </div>
            <div class="detail">
                <label for="full-name">Full Name:</label>
                <span id="full-name">MICHAEL ANTHONY REED</span>
            </div>
            <div class="detail">
                <label for="id-number">ID Number:</label>
                <span id="id-number"></span>
            </div>
            <div class="detail">
                <label for="email">Email:</label>
                <span id="email">mike@kirjo.co.za</span>
            </div>
            <div class="detail">
                <label for="contact-numbers">Contact:</label>
                <span id="contact-numbers">0824136471 /</span>
            </div>
          
            <div class="detail">
                <label for="patients">Patient:</label>
                <span id="patients">ROMAY ETHNE CLARICE REED [4902180019088]</span>
            </div>
            <div class="detail">
                <label for="scheme-name">Scheme:</label>
                <span id="scheme-name">Discovery Health Medical Scheme</span>
            </div>
            <div class="detail">
                <label for="scheme-option">Scheme Option:</label>
                <span id="scheme-option">Essential Delta Core</span>
            </div>
            <div class="detail">
                <label for="member-number">Member Number:</label>
                <span id="member-number">673327422</span>
            </div>
      
        </div>
    </div>

<div class="table-wrapper">
    <table class="fl-table">
        <thead>
        <tr><th>No.</th><th>CPT4</th><th>Inv.Dat</th><th>Modifier</th><th>Res. Code</th><th>Treat.Date</th><th>PMB?</th><th>Tarif.C</th><th>ICD10</th><th>Chrgd Amt</th>
                        <th>Sch. Amt</th><th>Memb.Port</th><th>GAP</th><th>Calc</th><th></th></tr>
        </thead>
        <tbody style="color:#9e9e9e; font-size:0.8rem">
        <tr id="new0033588">
            <td colspan="15" style="font-weight: bolder; text-align: center; color: deepskyblue">
            <a href="" class="uk-icon-button" uk-icon="plus-circle"></a> <a href="" class="uk-icon-button" uk-icon="comment"></a> <a href="" class="uk-icon-button" uk-icon="pencil"></a>
             <span class="doc-text" title="Scheme Savings : 0 Discount Savings : 0 VAS : 0"> <b>[0033588]</b> DR RF SMIT (0210072940  ext: 1018) [RST5237]</span>
                    </td>
                    </tr>
        <tr>
        <td><span class="uk-badge">1</span></td>
            <td></td>
            <td></td>
            <td>0000</td>
            <td>9000</td>
            <td>2003-09-09</td>
            <td><span class="pmb-badge" uk-icon="check"></span></td>
            <td>1012</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td><span class="uk-icon-button" uk-icon="pencil"></span> <span class="uk-icon-button" uk-icon="trash"></span></td>
        </tr>
        <tr>
        <td><span class="uk-badge">2</span></td>
            <td></td>
            <td></td>
            <td>0000</td>
            <td>9000</td>
            <td>2003-09-09</td>
            <td><span class="pmb-badge" uk-icon="check"></span></td>
            <td>1012</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td>234.90</td>
            <td><span class="uk-icon-button" uk-icon="pencil"></span> <span class="uk-icon-button" uk-icon="trash"></span></td>
        </tr>
<tr><th></th><th colspan="8"></th><th>0.00</th><th>0.00</th><th>0.00</th><th>0.00</th><th>0.00</th><th></th></tr>
<tr class="text-info"><th></th>
<th colspan="7"></th><th>[0.00]</th><th>[0.00]</th><th></th><th>[0.00]</th><th>
    <a style="display:block" claim_id="95351" practice_number="5806860" gap="0.00" charged="0.00" scheme="0.00" class="uk-margin-small-right text-success gapr uk-icon" uk-icon="pencil">
    
</a></th>
            <th>
                <a dr_name="Mediclinic Louis Leipoldt " gap="0.00" class="uk-margin-small-right text-success uk-icon-button uk-icon" id="calculator" uk-icon="settings">           
            </a>
                </th><th></th></tr>
                <tr style="background-color:#d1ece4"><th>Totals :</th>
                <th colspan="8">Scheme Savings : <span class="uk-badge">0.00</span> Discount Savings : <span class="uk-badge">0.00</span></th>
                <th><span style="color: #aa7700">36 479.80</span></th><th><span style="color: #aa7700">17 142.68</span></th>
                <th><span style="color: #aa7700">19 337.12</span></th><th><span style="color: #aa7700">9 668.56</span></th>
                <th>9 668.56</th>
                <th></th>
            </tr>

        <tbody>
    </table>
</div>
<hr class="uk-divider-icon">
<section id="notes_section"><div class="row"><div class="col-md-8"><ul class="tabs" style="color: #0b8278 !important; border-bottom: 1px solid #0b8278 !important">
                <li class="tab" onclick="openTab('notes_tab')"><a class="notes_tab inaction active" style="color: #0b8278;">Notes</a></li>
                <li class="tab" onclick="openTab('feedback_tab')"><a class="feedback_tab" style="color: #0b8278;">Feedback</a></li><li class="tab" onclick="openTab('validations_tab')"><a class="validations_tab" style="color: #0b8278;">Validations</a></li> <li class="indicator" style="left: 0px; right: 542px;"></li></ul></div><div class="col-md-4" style="border-bottom: 1px dashed grey !important;\"><label><label title="QA Box"><input type="checkbox" class="uk-checkbox qa_tick" id="yes_95351"><span>QA?</span><label> 
<label title="Send for Clinical Review" style="padding-left: 20px !important;"><input type="checkbox" id="no_95351" class="uk-checkbox clinical_review"><span>Clinical Review?</span><label></label></label></label></label></label></div><div id="notes_tab" class="col s12 uk-animation-fade detab" style="display: block;"><div class="row"><div class="col-md-7" style="overflow-y: scroll; height:500px;">  <div class="uk-comment-body" style="background-color: whitesmoke; padding: 10px; border-radius: 10px; color: red">
                                    <span class="nothing">No Client Feedback</span>
                                </div><hr><span id="t01"></span>  <div class="uk-comment-body" style="background-color: whitesmoke; padding: 10px; border-radius: 10px; color: red">
                                    <span class="nothing">No Notes</span>
                                </div></div> <div class="col-md-5" style="border: 1px solid whitesmoke"><div><span class="uk-text-meta purple-text"> No Files </span><form style="display: inline; padding: 5px" action="edit_case.php" id="vv" method="post"><input type="hidden" name="claim_id" value="95351"><button class="uk-button uk-button-primary uk-button-small" style="background-color: #54bf99;"><span uk-icon="pencil" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke="#000" d="M17.25,6.01 L7.12,16.1 L3.82,17.2 L5.02,13.9 L15.12,3.88 C15.71,3.29 16.66,3.29 17.25,3.88 C17.83,4.47 17.83,5.42 17.25,6.01 L17.25,6.01 Z"></path><path fill="none" stroke="#000" d="M15.98,7.268 L13.851,5.148"></path></svg></span> Edit Claim</button></form><span onclick="sendConsent(&quot;95351&quot;,&quot;&quot;)" title=""><button class="uk-button uk-button-primary uk-button-small" style="background-color: #54bf99;"><span id="consentID">Send Consent</span></button></span>
                          <div class="uk-inline" style="padding-right: 8px;"><span tabindex="0" aria-haspopup="true" aria-expanded="false"> <button class="uk-button uk-button-default uk-button-small" style="border: 1px solid #54bf99;">View Consent</button></span>
                          <button class="uk-button uk-button-default uk-button-small">Msg</button>
                          <div uk-dropdown="" class="uk-dropdown"><a href="consent_forms.php" onclick="window.open('consent_forms.php','popup','width=1100,height=700'); return false;" title="Click to view Consent Forms"><button class="uk-button uk-button-default uk-button-small" style="border: 1px solid #54bf99;"> Consent Forms</button></a>
                          <div><p></p></div><a href="mymessage.php?claim_id=95351" class="uk-button uk-button-default uk-button-small" style="border: 1px solid #54bf99;" onclick="window.open('mymessage.php?claim_id=95351','popup','width=800,height=600'); return false;" title="Click to view">View Message</a></div></div>    <div class="input-field col s12">

        <textarea class="materialize-textarea" data-length="10000" id="intervention_desc" name="intervention_desc" placeholder="Type your note here" onkeyup="valid()"></textarea>

    <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span></div>
    <div style="display: none; padding-left: 10px;" id="doc_detail1"><br>
        <div class="row">
            <div class="col-md-6"><span style="color:#53C099; font-weight: bolder" id="doc_name"> </span></div>
            <div class="col-md-6" id="doc_practiceno" style="color:#53C099;"></div>
        </div>
        <div class="row">
            <div class="col-md-4">Scheme Savings<input type="number" title="Scheme savings" id="doc_schemesavings" placeholder="Scheme" class="form-control"></div>
            <div class="col-md-4">Discount Savings<input type="number" title="Discount savings" id="doc_discountsavings" placeholder="Discount" class="form-control"></div>
            <div class="col-md-4">VAS<input type="number" title="Value Added savings" id="doc_vas" placeholder="VAS" class="form-control"></div>
        </div>
      <div class="row">
            
            <div class="col-md-6">Pay Provider? : <label>
                    <input type="radio" id="pay_doctor1" name="pay_doctor" value="yes">
                    <span>Yes</span>
                </label>
                <label>
                    <input type="radio" id="pay_doctor2" name="pay_doctor" value="no">
                    <span>No</span>
                </label>
            </div>
            <div class="col-md-6" style="border-left:1px solid #20c997">Scheme Declined? : <label>
                    <input type="radio" id="scheme_declined1" name="scheme_declined" value="yes">
                    <span>Yes</span>
                </label>
                <label>
                    <input type="radio" id="scheme_declined2" name="scheme_declined" value="no">
                    <span>No</span>
                </label>
            </div>
        </div>

    </div>
    <div class="input-field col s12 te" style="display:none"><div class="select-wrapper"><input class="select-dropdown dropdown-trigger" type="text" readonly="true" data-target="select-options-b91bc7f9-57a9-852b-71f8-a7bd134d8ceb"><ul id="select-options-b91bc7f9-57a9-852b-71f8-a7bd134d8ceb" class="dropdown-content select-dropdown" tabindex="0"></ul><svg class="caret" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg><select id="reason" class="reason" tabindex="-1"><option value="1">The Authorisation was obtained prior to the procedure therefore, this was a planned procedure.</option><option value="2">This was a Voluntary use of a non-designated service provider. The authorisation was obtained before the procedure.</option><option value="3">Please note, there are DSPs available in the allowable area.</option><option value="4">There are Associated Specialist Anaesthetists practicing at the facility that the procedure was performed at.</option><option value="5">Please note the ICD10 code is not PMB level of care.</option><option value="6">Even though the diagnosis is PMB, the procedure is not PMB level of care</option><option value="7">A decision was taken by Profmed regarding providers that claim more than 300% for Prescribed Minimum Benefit(PMB). Accounts will not be funded at 500%</option><option value="8">The diagnosis code is not a listed PMB condition. The application can, therefore, not be considered for PMB benefits.</option><option value="9">The Clinical information / reports received does not confirm the Emergency, therefore PMB at cost is declined.</option><option value="10">Condition is a non-listed Pmb condition, thus no Pmb entitlement.</option><option value="11">The claim cannot be reviewed from PMB?(Prescribed Minimum Benefit), as the provider is on the associated specialist network and should be billing according to the Momentum Health rate</option><option value="0">Select declined reason</option><option value="12">The claim will not be paid as a Prescribed Minimum Benefit (PMB), as this was a pre-booked case.</option><option value="13">This claim has been reviewed and declined to pay out at cost as this was a planned
admission. This was a voluntary use of a non-designated service provider.</option></select></div><label>Select Decline Reason</label></div>
   
    <div class="input-field col s12">
                                    <div class="select-wrapper"><input class="select-dropdown dropdown-trigger" type="text" readonly="true" data-target="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f936778"><ul id="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f936778" class="dropdown-content select-dropdown" tabindex="0"><li id="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f9367780" tabindex="0" class="selected"><span>Select Destination</span></li><li id="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f9367781" tabindex="0"><span>Provider</span></li><li id="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f9367782" tabindex="0"><span>Medical aid scheme</span></li><li id="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f9367783" tabindex="0"><span>Medical aid scheme and Provider</span></li><li id="select-options-5f38f5da-e3bc-78e8-3fbc-d8d84f9367784" tabindex="0"><span>Member</span></li></ul><svg class="caret" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg><select id="consent_dest" tabindex="-1">
                                        <option value="">Select Destination</option>
                                        <option value="Provider">Provider</option>
                                        <option value="Medical aid scheme">Medical aid scheme</option>
                                        <option value="Medical aid scheme and Provider">Medical aid scheme and Provider</option>
                                        <option value="Medical aid scheme">Member</option>
                                    </select></div>
                                    <label>Select</label>
                                </div> <div class="">
                            
                                Close Case?
                                <p id="not_closed">
                                    <label>
                                        <input type="radio" id="open" name="Open" value="1" checked="">
                                        <span>No</span>
                                    </label>
                                </p><p><label><input type="radio" id="close" name="Open" value="0"><span>Yes</span></label></p><button class="uk-button uk-button-primary uk-button-small" style="background-color: #54bf99;" id="addNotes" onclick="addNotes('95351;','0')" disabled="true"><span uk-icon="check" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span> Add Note</button>
                            <div style="display: none; padding: 12px" id="meshow">Please wait...</div>
                            </div></div></div></div></div><div id="feedback_tab" class="col s12 uk-animation-fade detab" style="display: none;"><p><u>Feedback</u></p><div class="row"><div class="col-md-8"><span id="t02"></span>  <div class="uk-comment-body" style="background-color: whitesmoke; padding: 10px; border-radius: 10px; color: red">
                                    <span class="nothing">No Client Feedback</span>
                                </div></div><div class="col-md-4"> <div class="select-wrapper"><input class="select-dropdown dropdown-trigger" type="text" readonly="true" data-target="select-options-879e793f-8b34-beb7-c852-f91f06903adf"><ul id="select-options-879e793f-8b34-beb7-c852-f91f06903adf" class="dropdown-content select-dropdown" tabindex="0"><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf0" tabindex="0" class="selected"><span>Select Reason</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf1" tabindex="0"><span>Not listed below</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf2" tabindex="0"><span>No feedback note for more than 2 days</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf3" tabindex="0"><span>Claim Number incorrect</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf4" tabindex="0"><span>Policy Number Incorrect</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf5" tabindex="0"><span>Wrong Doctor mentioned in note</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf6" tabindex="0"><span>Gap value in case doesn't match ours</span></li><li id="select-options-879e793f-8b34-beb7-c852-f91f06903adf7" tabindex="0"><span>Doctor Disputing discount</span></li></ul><svg class="caret" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg><select id="fxd" tabindex="-1"><option value="">Select Reason</option>
                            <option value="-">Not listed below</option>                            
                            <option value="No feedback note for more than 2 days">No feedback note for more than 2 days</option>
                            <option value="Claim Number incorrect">Claim Number incorrect</option>
                            <option value="Policy Number Incorrect">Policy Number Incorrect</option>
                            <option value="Wrong Doctor mentioned in note">Wrong Doctor mentioned in note</option>
                            <option value="Gap value in case doesn't match ours">Gap value in case doesn't match ours</option>
                            <option value="Doctor Disputing discount">Doctor Disputing discount</option>
                        </select></div><label for="fxd"> Select Reason</label><p><textarea rows="8" placeholder="add feedback here..." style="border-color: #0b8278; border-radius: 5px" cols="80" id="feedback_desc" name="feedback_desc" onkeyup="valid1()"></textarea></p><button class="uk-button uk-button-primary uk-button-small" onclick="addFeedback('95351')" id="addFeedback"><span><span uk-icon="mail" class="uk-icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polyline fill="none" stroke="#000" points="1.4,6.5 10,11 18.6,6.5"></polyline><path d="M 1,4 1,16 19,16 19,4 1,4 Z M 18,15 2,15 2,5 18,5 18,15 Z"></path></svg></span> Send Feedback</span> </button>
                        <span style="color: green;font-weight: bolder; display: none" id="feedbackShow">Sending, please wait...</span>
                        <div id="alert1" class="alert" style="display: none; width: 60%;"></div></div></div></div><div id="validations_tab" class="col s12 uk-animation-fade detab" style="display: none;"><p align="center">Validations</p><div id="validations"><table class="uk-table uk-table-divider"><thead><tr><th style="width: 20%">Number</th><th style="width: 20%">Rules</th><th>Description</th><th>Confirm</th></tr></thead> <tbody>        <tr><td><span class="uk-icon-button uk-margin-small-right uk-icon" uk-icon="check"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><polyline fill="none" stroke="#000" stroke-width="1.1" points="4,10 8,15 17,4"></polyline></svg></span></td>
            <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> The provider may not have applied modifier 0005 properly. <br>On Practice Number :<br>
                    <ul><li>2806835</li></ul>                </div></td><td> <div class="uk-card uk-card-default uk-card-body">
                    Modifier 0005 decreases the price chargeable for the second and subsequent procedures performed under the same anaesthetic                </div></td><td><label><input id="provider_zf" class="uk-checkbox" type="checkbox" onclick="showhide('provider_zf','membspan2')"> <span>Confirm?</span></label><span id="membspan2" style="display: none"> <textarea class="uk-textarea" id="memtxt2"></textarea><button class="uk-button uk-button-primary uk-button-small" onclick="provider_zf('95351')">Update</button></span><ul id="myspan2"><ul></ul></ul></td></tr>
           <tr>
   <td><span class="uk-badge">4</span> <span class="uk-badge">5</span> <span class="uk-badge">6</span> <span class="uk-badge">7</span> </td>
                        <td><div class="uk-text-danger uk-card uk-card-default uk-card-body"> <ul><li>Mismatch on Tariff and ICD10 codes</li></ul></div></td>
                        <td>  <div class="uk-card uk-card-default uk-card-body">
                             If the claimed ICD10 code is not in the list returned then there is a possible diagnosis to procedure mismatch.
                             <h4 align="center"> <form method="post" action="http://greenwest.co.za/clinical/index.php" target="_blank"><button name="clinical" class="uk-button uk-button-primary">Code look up</button></form></h4>

                             
                            </div></td>
                        <td><label><input id="codingcptcodingcpt" style="opacity: 200 !important; position: relative !important;" class="uk-checkbox" type="checkbox" onclick="showhide(&quot;codingcptcodingcpt&quot;,&quot;membspan4&quot;)"> Confirm?</label><span id="membspan4" style="display: none"> <textarea class="uk-textarea" id="memtxt4"></textarea><button class="uk-button uk-button-primary uk-button-small" onclick="updateCoding(&quot;95351&quot;)">Update</button></span><ul id="myspan4"></ul></td>
                    </tr></tbody></table><input type="hidden" id="xjson" value="provider_zf,coding_checked"></div></div></div></section>
    </div>
    <script>
    const first = document.querySelector(".first");
const second = document.querySelector(".second");
const third = document.querySelector(".third");
const fourth = document.querySelector(".fourth");
const fifth = document.querySelector(".fifth");
const sixth = document.querySelector(".sixth");
const steps = [first, second, third, fourth, fifth,sixth];

function nextStep(currentStep) {
    steps.forEach(step => step.classList.remove("active"));

    steps.forEach((step, index) => {
        if (index <= currentStep) {
            step.classList.add("active");
        } else {
            step.classList.remove("active");
        }
    });
}

steps.forEach((step, index) => {
    step.addEventListener("click", () => {
        nextStep(index);
    });
});
$(document).on('click','.sticky-button',function(){
    console.log('testing...');
    $('.process').slideToggle();
});

</script>
</body>
</html>
