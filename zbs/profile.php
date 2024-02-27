<?php
session_start();
define("access",true);
require_once ("header.php");
include ("classes/DBConnect.php");
$db=new DBConnect();
$user_id=$_SESSION["user_id"];
$row=$db->getProfile($user_id);
$name=$row["name"];
$surname=$row["surname"];
$practice_number=$row["practice_number"];
$displine=$row["discipline"];
$email=$row["email"];
$contact_number=$row["contact_number"];
//$patient_id=$row["patient_id"];
?>
<style>
    .password{
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .username{
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .pbtn{
        width: 50% !important;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .et_pb_contact_submit{
        margin-left: 35% !important;
    }
    .talign{
        margin-left: 35% !important;
        padding-top: 5px;
        font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
        font-weight: 300;
        line-height: 1.6em;
    }

    @media only screen and (max-width: 600px) {
        .password{
            width: 100% !important;
        }
        .username{
            width: 100% !important;
        }
        .et_pb_contact_submit{
            margin-left: 18px !important;
        }
        .talign{
            margin-left: 18px !important;
            padding-top: 5px;
            font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif;
            font-weight: 300;
            line-height: 1.6em;
        }
    }

</style>
<script>
    $(document).ready(function(){

        $("#submitbtn").click(function(){
            $("#load").show();
            var first_name=$("#first_name").val();
            var last_name=$("#last_name").val();
            var descipline=$("#descipline").val();
            var contact_number=$("#contact_number").val();
            var practice_number=$("#practice_number").val();
            //var patient_id=$("#patient_id").val();

            $.ajax({
                url:"ajax/process.php",
                type:"POST",
                data:{
                    identity_number:4,
                    first_name:first_name,
                    last_name:last_name,
                    descipline:descipline,
                    contact_number:contact_number,
                    practice_number:practice_number

                },
                success:function(data)
                {
                    $("#load").hide();
                    $("#msg").html(data);
                    if(data.indexOf("Success")>-1)
                    {
                        $("#mod").click();
                    }
                },
                error:function(jqXHR, exception)
                {
                    $("#load").hide();
                    alert("There is an error : "+jqXHR.responseText);
                }

            });
        });
    });
</script>
<div class="et_pb_code_inner">

    <div class="">
        <div class="et_pb_row et_pb_row_1">
            <div class="et_pb_column et_pb_column_4_4 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough et-last-child">
                <div class="et_pb_module et_pb_code et_pb_code_0">
                    <div class="et_pb_code_inner"><div class="et_pb_contact">
                            <div class="et_pb_contact_form clearfix" id="main1">
                                <h1 align="center" class="et_pb_textb">My Details</h1>
                                <p class="et_pb_contact_field et_pb_contact_field_0 et_pb_contact_field_half" data-id="first_name" data-type="input"> <label class="et_pb_contact_form_label">First Name</label> <input type="text" class="input" value="<?php echo $name;?>" name="first_name" data-required_mark="required" data-field_type="input" id="first_name" placeholder="First Name"> </p>  <p class="et_pb_contact_field et_pb_contact_field_1 et_pb_contact_field_half et_pb_contact_field_last" data-id="last_name" data-type="input">
                                    <label class="et_pb_contact_form_label">Last Name</label>
                                    <input type="text" class="input" value="<?php echo $surname;?>" name="et_pb_contact_last_name_0" data-required_mark="required" data-field_type="input" id="last_name" placeholder="Last Name"> </p>
                                <p class="et_pb_contact_field et_pb_contact_field_2 et_pb_contact_field_half" data-id="contact_number" data-type="input">
                                    <label class="et_pb_contact_form_label">Contact Number</label>
                                    <input type="text" class="input" value="<?php echo $contact_number;?>" name="et_pb_contact_contact_number_0" data-required_mark="required" data-field_type="input" id="contact_number" placeholder="Contact Number" minlength="9" maxlength="11" onkeypress="return isNumber(event)"> </p>
                                <p class="et_pb_contact_field et_pb_contact_field_3 et_pb_contact_field_half et_pb_contact_field_last" data-id="email" data-type="email">
                                    <label class="et_pb_contact_form_label">Email Address</label>
                                    <input type="text" class="input" value="<?php echo $email;?>" name="et_pb_contact_email_0" data-required_mark="required" data-field_type="email" id="email" placeholder="Email Address"> </p>
                                <p class="et_pb_contact_field et_pb_contact_field_4 et_pb_contact_field_half" data-id="practice_number" data-type="input">
                                    <label class="et_pb_contact_form_label">Practice Number</label>
                                    <input type="text" class="input" value="<?php echo $practice_number;?>" name="practice_number" data-required_mark="required" data-field_type="input" id="practice_number" placeholder="Practice Number" minlength="6" maxlength="13" onkeypress="return isNumber(event)"> </p>
                                <p class="et_pb_contact_field et_pb_contact_field_5 et_pb_contact_field_half et_pb_contact_field_last" data-id="descipline" data-type="input"> <label class="et_pb_contact_form_label">Discipline</label>
                                    <select id="descipline" name="et_pb_contact_email_0" class="input" data-required_mark="required" data-field_type="input" style="-webkit-border-radius: 0;-webkit-appearance: none;background-color: #eee;width: 100%;border-width: 0;border-radius: 0;color: #999;font-size: 14px;padding: 16px;"><option value="<?php echo $displine;?>"><?php echo $displine;?></option><option value="003 --- 000 --- Accredited Blood and Blood Product Couriers ---">003 --- 000 --- Accredited Blood and Blood Product Couriers --- </option><option value="004 --- 000 --- Chiropractors">004 --- 000 --- Chiropractors --- </option><option value="3">007 --- 000 --- Namibian Practitioners Only (Not recognized by (HPCSA) --- </option><option value="4">007 --- 001 --- Namibian Practitioners Only (Not recognized by (HPCSA) --- Acupuncturist</option><option value="5">007 --- 002 --- Namibian Practitioners Only (Not recognized by (HPCSA) --- Reflexologists</option><option value="6">007 --- 003 --- Namibian Practitioners Only (Not recognized by (HPCSA) --- Forensic</option><option value="7">008 --- 000 --- Homeopaths --- </option><option value="8">009 --- 000 --- Ambulance Services - Advanced --- </option><option value="9">009 --- 001 --- Ambulance Service --- Basic Life Support Service</option><option value="10">009 --- 002 --- Ambulance Service --- Intermediate Life Support Service</option><option value="11">009 --- 003 --- Ambulance Service --- Advance Life Support Service</option><option value="12">009 --- 004 --- Ambulance Service --- Provincial Ambulance Service</option><option value="13">010 --- 000 --- Anaesthetists --- </option><option value="14">011 --- 000 --- Ambulance Services - Intermediate --- </option><option value="15">012 --- 000 --- Dermatology --- </option><option value="16">013 --- 000 --- Ambulance Services - Basic --- </option><option value="17">014 --- 000 --- General Medical Practice --- </option><option value="18">014 --- 001 --- General Medical Practice --- Cardiology (Lesotho Practitioners Only)</option><option value="19">014 --- 002 --- General Medical Practice --- Sexual Health (Lesotho Practitioners only)</option><option value="20">015 --- 000 --- Specialist Family Medicine --- </option><option value="21">016 --- 000 --- Independent Practice Specialist Obstetrics and Gynaecology --- </option><option value="22">016 --- 001 --- Independent Practice Sub Specialist Obstetrics and Gynaecology --- Critical Care</option><option value="23">016 --- 002 --- Independent Practice Sub Specialist Obstetrics and Gynaecology --- Gynaecological Oncology</option><option value="24">016 --- 003 --- Independent Practice Sub Specialist Obstetrics and Gynaecology --- Medical Genetics</option><option value="25">016 --- 004 --- Independent Practice Sub Specialist Obstetrics and Gynaecology --- Maternal and Foetal Medicine</option><option value="26">016 --- 005 --- Independent Practice Sub Specialist Obstetrics and Gynaecology --- Reproductive Medicine</option><option value="27">016 --- 006 --- Independent Practice Sub Specialist Obstetrics and Gynaecology --- Infectious Diseases</option><option value="28">017 --- 000 --- Pulmonology --- </option><option value="29">018 --- 000 --- Independent Practice Specialist Medicine --- </option><option value="30">018 --- 001 --- Independent Practice Subspecialist Medicine --- Clinical Haematology</option><option value="31">018 --- 002 --- Independent Practice Subspecialist Medicine --- Nephrology</option><option value="32">018 --- 003 --- Independent Practice Subspecialist Medicine --- Cardiology</option><option value="33">018 --- 004 --- Independent Practice Subspecialist Medicine --- Endocrinology</option><option value="34">018 --- 005 --- Independent Practice Subspecialist Medicine --- Pulmonology</option><option value="35">018 --- 006 --- Independent Practice Subspecialist Medicine --- Critical Care</option><option value="36">018 --- 007 --- Independent Practice Subspecialist Medicine --- Geriatric Medicine</option><option value="37">018 --- 008 --- Independent Practice Subspecialist Medicine --- Medical Genetics</option><option value="38">018 --- 009 --- Independent Practice Subspecialist Medicine --- Infectious Diseases</option><option value="39">018 --- 010 --- Independent Practice Subspecialist Medicine --- Gastroenterology</option><option value="40">018 --- 011 --- Independent Practice Subspecialist Medicine --- Medical Oncology</option><option value="41">018 --- 012 --- Independent Practice Subspecialist Medicine --- Rheumatology</option><option value="42">019 --- 000 --- Gastroenterology --- </option><option value="43">020 --- 000 --- Neurology --- </option><option value="44">021 --- 000 --- Cardiology --- </option><option value="45">021 --- 001 --- Cardiology Independent Practice Sub Specialist --- Medicine</option><option value="46">021 --- 002 --- Cardiology Independent Practice Sub Specialist --- Paediatrics</option><option value="47">021 --- 003 --- Cardiology Independent Practice Sub Specialist --- Special Merit</option><option value="48">022 --- 000 --- Psychiatry --- </option><option value="49">023 --- 000 --- Medical Oncology --- </option><option value="50">024 --- 000 --- Independent Practice Specialist Neurosurgery --- </option><option value="51">024 --- 001 --- Independent Practice Sub Specialist Neurosurgery --- Critical Care</option><option value="52">025 --- 000 --- Nuclear Medicine --- </option><option value="53">026 --- 000 --- Ophthalmology --- </option><option value="54">027 --- 000 --- Clinical Haemotology --- </option><option value="55">027 --- 001 --- Clinical Haematology Independent Practice Sub Specialist --- Pathology (Haematological)</option><option value="56">027 --- 002 --- Clinical Haematology --- Paediatrics</option><option value="57">027 --- 003 --- Clinical Haematology --- Medicine</option><option value="58">028 --- 000 --- Orthopaedics --- </option><option value="59">029 --- 000 --- Occupational Medicine Independent Practice Specialist --- </option><option value="60">030 --- 000 --- Otorhinolaryngology --- </option><option value="61">031 --- 000 --- Rheumatology --- </option><option value="62">032 --- 000 --- Paediatrics Independent Practice Specialist --- </option><option value="63">032 --- 001 --- Paediatrics Independent Practice Sub Specialist --- Neurology</option><option value="64">032 --- 002 --- Paediatrics Independent Practice Sub Specialist --- Developmental Paediatrics</option><option value="65">032 --- 003 --- Paediatrics Independent Practice Sub Specialist --- Medical Oncology</option><option value="66">032 --- 004 --- Paediatrics Independent Practice Sub Specialist --- Infectious Diseases</option><option value="67">032 --- 005 --- Paediatrics Independent Practice Sub Specialist --- Medical Genetics</option><option value="68">032 --- 006 --- Paediatrics Independent Practice Sub Specialist --- Endocrinology</option><option value="69">032 --- 007 --- Paediatrics Independent Practice Sub Specialist --- Gastroenterology</option><option value="70">032 --- 008 --- Paediatrics Independent Practice Sub Specialist --- Neonatology</option><option value="71">032 --- 009 --- Paediatrics Independent Practice Sub Specialist --- Pulmonology</option><option value="72">032 --- 010 --- Paediatrics Independent Practice Sub Specialist --- Rheumatology</option><option value="73">032 --- 011 --- Paediatrics Independent Practice Sub Specialist --- Nephrology</option><option value="74">032 --- 012 --- Paediatrics Independent Practice Sub Specialist --- Critical Care</option><option value="75">032 --- 013 --- Paediatrics Independent Practice Sub Specialist --- Cardiology</option><option value="76">032 --- 014 --- Paediatrics Independent Practice Sub Specialist --- Clinical Haematology</option><option value="77">033 --- 000 --- Paed. Cardiology --- </option><option value="78">034 --- 000 --- Physical Medicine --- </option><option value="79">035 --- 000 --- Emergency Medicine Independent Practice Specialist --- </option><option value="80">036 --- 000 --- Plastic and Reconstructive Surgery --- </option><option value="81">037 --- 000 --- Medical technology --- </option><option value="82">037 --- 001 --- Medical technology --- Blood Transfusion Technology</option><option value="83">037 --- 002 --- Medical technology --- Cardiology</option><option value="84">037 --- 003 --- Medical technology --- Chemical Pathology</option><option value="85">037 --- 004 --- Medical technology --- Clinical Pathology</option><option value="86">037 --- 005 --- Medical technology --- Cytotechnology</option><option value="87">037 --- 006 --- Medical technology --- Forensic Pathology</option><option value="88">037 --- 007 --- Medical technology --- Haematology</option><option value="89">037 --- 008 --- Medical technology --- Histopathological Technique</option><option value="90">037 --- 009 --- Medical technology --- Lung Function</option><option value="91">037 --- 010 --- Medical technology --- Microbiology</option><option value="92">037 --- 011 --- Medical technology --- Parasitology</option><option value="93">037 --- 012 --- Medical technology --- Pharmacology</option><option value="94">037 --- 013 --- Medical technology --- Virology</option><option value="95">037 --- 014 --- Medical technology --- Immunology</option><option value="96">037 --- 015 --- Medical technology --- Radio-Isotope Technology</option><option value="97">038 --- 000 --- Diagnostic Radiology --- </option><option value="98">039 --- 000 --- Radiography --- </option><option value="99">040 --- 000 --- Independent Practice Specialist Radiation Oncology --- </option><option value="100">042 --- 000 --- Surgery/Paediatric surgery Independent Practice Specialist --- </option><option value="101">042 --- 001 --- Surgery Independent Practice Sub Specialist --- Vascular Surgery</option><option value="102">042 --- 002 --- Surgery Independent Practice Sub Specialist --- Critical Care</option><option value="103">042 --- 003 --- Surgery Independent Practice Sub Specialist --- Gastroenterology</option><option value="104">042 --- 004 --- Surgery Independent Practice Sub Specialist --- Paediatric Surgery</option><option value="105">044 --- 000 --- Cardio Thoracic Surgery --- </option><option value="106">046 --- 000 --- Urology --- </option><option value="107">047 --- 000 --- Drug &amp; Alcohol Rehab --- (Department of Health)</option><option value="108">047 --- 001 --- Drug &amp; Alcohol Rehab --- (Welfare)</option><option value="109">048 --- 000 --- Travel Clinic --- </option><option value="110">049 --- 000 --- Sub-Acute Facilities --- </option><option value="111">049 --- 001 --- Sub-Acute Facilities --- General Care</option><option value="112">049 --- 002 --- Sub-Acute Facilities --- Psychiatry</option><option value="113">049 --- 003 --- Sub-Acute Facilities --- Physical Rehab</option><option value="114">049 --- 004 --- Sub-Acute Facilities --- All Services</option><option value="115">049 --- 005 --- Sub-Acute Facilities --- Post Natal Unit</option><option value="116">049 --- 006 --- Sub-Acute Facilities --- Psychiatric/Post Natal</option><option value="117">049 --- 007 --- Sub-Acute Facilities --- Rehab/Post Natal</option><option value="118">049 --- 008 --- Sub-Acute Facilities --- Specialised Psychiatric Unit Only</option><option value="119">050 --- 000 --- Group practices --- </option><option value="120">050 --- 009 --- Group Practice --- Primary Care</option><option value="121">050 --- 010 --- Delayed Children Development Clinic --- WELFARE &amp; DOH</option><option value="122">051 --- 000 --- Group practices/Hospitals --- </option><option value="123">052 --- 000 --- Pathology Independent Practice Specialist --- </option><option value="124">052 --- 001 --- Pathology Independent Practice Sub-Specialist --- Anatomy</option><option value="125">052 --- 002 --- Pathology Independent Practice Sub-Specialist --- Chemical</option><option value="126">052 --- 003 --- Pathology Independent Practice Sub-Specialist --- Clinical</option><option value="127">052 --- 004 --- Pathology Independent Practice Sub-Specialist --- Forensic</option><option value="128">052 --- 005 --- Pathology Independent Practice Sub-Specialist --- Clinical Haematology</option><option value="129">052 --- 006 --- Pathology Independent Practice Sub-Specialist --- Medical Genetics</option><option value="130">052 --- 007 --- Pathology Independent Practice Sub-Specialist --- Microbiology</option><option value="131">052 --- 008 --- Pathology Independent Practice Sub-Specialist --- Infectious Diseases</option><option value="132">052 --- 009 --- Pathology Independent Practice Sub-Specialist --- Virology</option><option value="133">054 --- 000 --- General Dental Practice --- </option><option value="134">055 --- 000 --- Mental Health Institutions --- </option><option value="135">056 --- 000 --- Provincial Hospitals --- </option><option value="136">056 --- 001 --- Provincial Hospitals --- District Hospital</option><option value="137">056 --- 002 --- Provincial Hospitals --- Regional Hospital</option><option value="138">056 --- 003 --- Provincial Hospitals --- Tertiary/Epidemic Hospital</option><option value="139">056 --- 004 --- Provincial Hospitals --- DOH Oral healthcare Centre</option><option value="140">056 --- 009 --- Provincial Hospitals --- Primary Care</option><option value="141">056 --- 010 --- Provincial Hospitals --- DOH Orthotics &amp; Prosthetics Centre</option><option value="142">057 --- 000 --- Private Hospitals ('A' - Status) --- </option><option value="143">057 --- 001 --- Private Hospitals ('A' - Status) --- +ICU +Theatre Less than 100 beds</option><option value="144">057 --- 002 --- Private Hospitals ('A' - Status) --- -ICU +Theatre</option><option value="145">057 --- 003 --- Private Hospitals ('A' - Status) --- +ICU -Theatre</option><option value="146">057 --- 004 --- Private Hospitals ('A' - Status) --- -ICU -Theatre</option><option value="147">057 --- 005 --- Private Hospitals ('A' - Status) --- +Theatre Maternity only</option><option value="148">057 --- 006 --- Private Hospitals ('A' - Status) --- -Theatre Maternity only</option><option value="149">057 --- 007 --- Private Hospital --- Namibian Rural Health Centres</option><option value="150">057 --- 008 --- Private Hospital Lesotho --- -ICU + Theatre</option><option value="151">057 --- 100 --- Private Hospitals ('A' - Status) --- Mine Hospilals</option><option value="152">057 --- 200 --- Private Hospitals ('A' - Status) --- State subsidised</option><option value="153">058 --- 000 --- Private Hospitals ('B' - Status) --- </option><option value="154">059 --- 000 --- Private Rehab Hospital (Acute) --- </option><option value="155">060 --- 000 --- Pharmacies --- </option><option value="156">061 --- 000 --- Pharmacotherapist --- </option><option value="157">062 --- 000 --- Maxillo-facial and Oral Surgery --- </option><option value="158">064 --- 000 --- Orthodontics --- </option><option value="159">065 --- 000 --- Counsellor(Lesotho Practitioners only) --- </option><option value="160">066 --- 000 --- Occupational Therapy --- </option><option value="161">067 --- 000 --- Art Therapists --- </option><option value="162">068 --- 000 --- Podiatry --- </option><option value="163">069 --- 000 --- Medical Scientist --- Clinical Biochemist</option><option value="164">069 --- 001 --- Medical Scientist --- Genetic Councillor</option><option value="165">069 --- 002 --- Medical Scientist --- Medical Biological Scientist</option><option value="166">069 --- 003 --- Medical Scientist --- Medical Physicist</option><option value="167">070 --- 000 --- Optometrists --- </option><option value="168">070 --- 001 --- Supplementary Optometrists --- Visual Science,Ocular Pathology &amp; Dispensing of Spectacles</option><option value="169">071 --- 000 --- Optical dispensers --- </option><option value="170">071 --- 001 --- Supplementary Optical dispensers --- No limitations</option><option value="171">072 --- 000 --- Physiotherapists --- </option><option value="172">073 --- 000 --- Masseurs --- </option><option value="173">074 --- 000 --- Orthoptists --- </option><option value="174">075 --- 000 --- Clinical technology --- Cardiology</option><option value="175">075 --- 001 --- Clinical technology --- Cardio-Vascular</option><option value="176">075 --- 002 --- Clinical technology --- Pulmonology/Nephrology</option><option value="177">075 --- 004 --- Clinical technology --- Reproductive biology</option><option value="178">075 --- 005 --- Medical technology --- Pathology</option><option value="179">075 --- 006 --- Clinical technology --- Neurophysiology</option><option value="180">075 --- 007 --- Clinical technology --- Critical Care</option><option value="181">075 --- 009 --- Biokinetics --- </option><option value="182">076 --- 000 --- Unattached operating theatres / Day clinics --- </option><option value="183">077 --- 000 --- Approved U O T U / Day clinics --- </option><option value="184">078 --- 000 --- Blood transfusion services --- </option><option value="185">079 --- 000 --- Hospices --- </option><option value="186">079 --- 001 --- Hospices --- SA Cancer Associations</option><option value="187">080 --- 000 --- Nursing Agencies/Home Care Services --- </option><option value="188">081 --- 000 --- Registered Councellors --- </option><option value="189">082 --- 000 --- Speech therapy and Audiology --- </option><option value="190">082 --- 001 --- Speech Therapy --- Only</option><option value="191">082 --- 002 --- Audiology --- Only</option><option value="192">083 --- 000 --- Hearing Aid Acoustician --- </option><option value="193">084 --- 000 --- Dieticians --- </option><option value="194">085 --- 000 --- Psychometry --- </option><option value="195">086 --- 000 --- Psychologists --- </option><option value="196">087 --- 000 --- Orthotists &amp; Prosthetists --- </option><option value="197">088 --- 000 --- Registered nurses --- </option><option value="198">088 --- 001 --- Registered nurses --- Midwife only</option><option value="199">088 --- 002 --- Registered nurses --- Psychiatric only</option><option value="200">088 --- 009 --- Registered nurses --- Primary Care</option><option value="201">089 --- 000 --- Social workers --- </option><option value="202">090 --- 000 --- Clinical services --- </option><option value="203">090 --- 001 --- Clinical services --- Oxygen Supplier</option><option value="204">090 --- 002 --- Clinical services --- Wheelchairs Supplier</option><option value="205">090 --- 003 --- Clinical services --- Ear &amp; Voice Prosthetic Supplier</option><option value="206">090 --- 004 --- Clinical services --- Eye Prosthetic Supplier</option><option value="207">090 --- 005 --- Clinical services --- Breast Prosthetic Supplier</option><option value="208">090 --- 006 --- Clinical services --- Cardiac Prosthetic Supplier</option><option value="209">090 --- 007 --- Clinical services --- Stomal/appliances Supplier</option><option value="210">090 --- 008 --- Clinical services --- Medical General Supplier</option><option value="211">090 --- 009 --- Clinical services --- FAMSA (family and marriage counselling)</option><option value="212">090 --- 010 --- Clinical services --- Employer Primary Care Facilities (NAMIBIA Only)</option><option value="213">090 --- 011 --- Clinical services --- Oncology units (not owned by hospital)</option><option value="214">090 --- 012 --- Clinical services --- Namibia Clinics</option><option value="215">090 --- 013 --- Clinical services --- Diabetes Appliances</option><option value="216">090 --- 014 --- Clinical Services --- Compression Bandaging &amp; Bone Healing System</option><option value="217">090 --- 015 --- Clinical Services --- Parenteral Nutrition (TPN) - homecare</option><option value="218">091 --- 000 --- Biokinetics --- </option><option value="219">092 --- 000 --- Periodontics --- </option><option value="220">093 --- 000 --- Dental Technician --- </option><option value="221">094 --- 000 --- Prosthodontic --- </option><option value="222">095 --- 000 --- Dental therapy --- </option><option value="223">096 --- 000 --- Community dentistry --- </option><option value="224">097 --- 000 --- Community health --- </option><option value="225">098 --- 000 --- Oral pathology --- </option><option value="226">099 --- 000 --- Psychological Counsellors - Namibian Practitioners Only --- </option><option value="227">101 --- 000 --- Naturopathy --- </option><option value="228">102 --- 000 --- Osteopathy --- </option><option value="229">103 --- 000 --- Phytotherapy --- </option><option value="230">104 --- 000 --- Ayurveda --- Ayurveda Practitioner</option><option value="231">104 --- 001 --- Ayurveda --- Primary Healthcare Advisor</option><option value="232">104 --- 002 --- Ayurveda --- Yoga Therapist</option><option value="233">105 --- 000 --- Acupuncturist --- </option><option value="234">106 --- 000 --- Therapeutic Aromatherapist --- </option><option value="235">107 --- 000 --- Therapeutic Massage Therapist --- </option><option value="236">108 --- 000 --- Therapeutic Reflexologist --- </option><option value="237">109 --- 000 --- Unani-Tibb --- </option></select> </p>

                                <div class="et_contact_bottom_container"> <button id="submitbtn" style="margin-left: 18px !important;" type="submit" name="et_builder_submit_button" class="et_pb_contact_submit et_pb_button">Save Now <span id="load" style="display: none" uk-spinner></span></button> </div>
                                <input type="hidden" id="_wpnonce-et-pb-contact-form-submitted-0" name="_wpnonce-et-pb-contact-form-submitted-0" value="6aa30265cc"> <input type="hidden" name="_wp_http_referer" value="/"> </div>
                            <div id="info" class="et_pb_column et_pb_column_1_3 et_pb_column_8 et_pb_css_mix_blend_mode_passthrough et-last-child" style="font-size:16px; font-weight: bold; color:red"> </div>

                        </div>
                </div> <!-- .et_pb_code -->

            </div> <!-- .et_pb_column -->

            </div>
            <div class="et_contact_bottom_container" id="msg"></div>
        </div> <!-- .et_pb_row -->


    </div>
    <!-- .et_pb_code -->
    <!-- #page-container -->

    <script type="text/javascript" id="divi-custom-script-js-extra">
        /* <![CDATA[ */
        var DIVI = {"item_count":"%d Item","items_count":"%d Items"};
        var et_shortcodes_strings = {"previous":"Previous","next":"Next"};
        var et_pb_custom = {"ajaxurl":"https:\/\/medclaimassist.co.za\/wp-admin\/admin-ajax.php","images_uri":"https:\/\/medclaimassist.co.za\/wp-content\/themes\/Divi\/images","builder_images_uri":"https:\/\/medclaimassist.co.za\/wp-content\/themes\/Divi\/includes\/builder\/images","et_frontend_nonce":"f5e56487fb","subscription_failed":"Please, check the fields below to make sure you entered the correct information.","et_ab_log_nonce":"a297160c78","fill_message":"Please, fill in the following fields:","contact_error_message":"Please, fix the following errors:","invalid":"Invalid email","captcha":"Captcha","prev":"Prev","previous":"Previous","next":"Next","wrong_captcha":"You entered the wrong number in captcha.","wrong_checkbox":"Checkbox","ignore_waypoints":"no","is_divi_theme_used":"1","widget_search_selector":".widget_search","ab_tests":[],"is_ab_testing_active":"","page_id":"994","unique_test_id":"","ab_bounce_rate":"5","is_cache_plugin_active":"yes","is_shortcode_tracking":"","tinymce_uri":""}; var et_builder_utils_params = {"condition":{"diviTheme":true,"extraTheme":false},"scrollLocations":["app","top"],"builderScrollLocations":{"desktop":"app","tablet":"app","phone":"app"},"onloadScrollLocation":"app","builderType":"fe"}; var et_frontend_scripts = {"builderCssContainerPrefix":"#et-boc","builderCssLayoutPrefix":"#et-boc .et-l"};
        var et_pb_box_shadow_elements = [];
        var et_pb_motion_elements = {"desktop":[],"tablet":[],"phone":[]};
        var et_pb_sticky_elements = [];
        /* ]]> */
    </script>
    <script type="text/javascript" src="https://medclaimassist.co.za/wp-content/themes/Divi/js/custom.unified.js?ver=4.7.6" id="divi-custom-script-js"></script>

    <!-- This is the modal -->

</div>
</body></html>