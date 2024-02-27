import { Text, TextInput, TouchableOpacity, View} from 'react-native'
import React,{useState,useEffect} from 'react';
import styles from './signin.style';
import {Formik} from 'formik';
import * as Yup from 'yup';
import { COLORS, SIZES } from '../../constants/theme';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { HeightSpacer, ReusableBtn, ReusableText, SuccessRegistrationAlert, WidthSpacer } from '../../components';
import useAxiosFetch from '../../hooks/use-axios';

const validationSchema = Yup.object().shape({
    member_id: Yup.number().integer().required()
  .min(1,"Invalid Member ID")
  .required('Required'),

  full_name:Yup.string()
  .min(3,"Invalid Name")
  .required('Required'),

  contact_number:Yup.number().integer().required()
  .min(8,"Invalid Contact Number")
  .required('Required')
})


const Registration = () => {
    const [signupBtnClicked, setSignupBtnClicked] = useState(false);
  const [postData, setPostData] = useState({});
  const [obsecureText,setObsecureText] = useState(false)

  const { isLoading: isLoadingAddButton, isError: isErrorAddButton, data: dataAddButton,statusCode: statusCodeSignup } = useAxiosFetch('signup','POST',postData,signupBtnClicked);


  useEffect(() => {
    if(signupBtnClicked)
    {   
      setSignupBtnClicked(false);       
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [signupBtnClicked]);
  const handleSubmitValues = (values) => {
    setPostData(values); 
    setSignupBtnClicked(true);
};

if(dataAddButton && statusCodeSignup===200)
    {
        console.log("Tendai Fuma");
        console.log(dataAddButton);
        return (<><TouchableOpacity style={styles.container1}><HeightSpacer height={20}/><ReusableText text={`ZBS Account was successfully created, you may SIGNIN into your account.`} align={"center"} family={'medium'} size={SIZES.medium} color={COLORS.green}/></TouchableOpacity><SuccessRegistrationAlert message={dataAddButton.message}/></>);
    }

  return (
    <View style={styles.container}>
        <Formik
        initialValues={{member_id:"",full_name:"",contact_number:""}}
        validationSchema={validationSchema}
        onSubmit={(value) =>{
            console.log("Testing");
            console.log(value);
        }}
        >
{({
    handleChange,
    touched,
    handleSubmit,
    values,
    errors,
    isValid,
    setFieldTouched,
}) =>(
  <View style={{paddingTop:30}}>
            <View style={styles.wrapper}>
            <Text style={styles.label}>Member ID</Text>
            <View>
        <View style={styles.inputWrapper(touched.full_name ? COLORS.lightBlue:COLORS.green)}>
            <MaterialCommunityIcons
            name='face-man-outline'
            size={20}
            color={COLORS.green}
            />
<WidthSpacer width={10}/>
            <TextInput
            placeholder='Enter Member ID'
            onFocus={() => {setFieldTouched('member_id')}}
            onBlur={() => {setFieldTouched('member_id',"")}}
            autoCorrect={false}
            autoCapitalize='none'
            value={values.member_id}
            onChangeText={handleChange('member_id')}
            style={{flex:1}}
            />
        </View>
        {touched.member_id && errors.member_id && (
            <Text style={styles.errorMessage}>
                {errors.member_id}
            </Text>
        )}
        </View>
        </View>
        <View style={styles.wrapper}>
            <Text style={styles.label}>Full Name</Text>
            <View>
        <View style={styles.inputWrapper(touched.full_name ? COLORS.lightBlue:COLORS.green)}>
            <MaterialCommunityIcons
            name='account-outline'
            size={20}
            color={COLORS.green}
            />
<WidthSpacer width={10}/>
            <TextInput
            placeholder='Enter Full Name'
            onFocus={() => {setFieldTouched('full_name')}}
            onBlur={() => {setFieldTouched('full_name',"")}}
            autoCorrect={false}
            autoCapitalize='none'
            value={values.full_name}
            onChangeText={handleChange('full_name')}
            style={{flex:1}}
            />
        </View>
        {touched.full_name && errors.full_name && (
            <Text style={styles.errorMessage}>
                {errors.full_name}
            </Text>
        )}
        </View>
        </View>

        <View style={styles.wrapper}>
            <Text style={styles.label}>Contact Number</Text>
            <View>
        <View style={styles.inputWrapper(touched.contact_number ? COLORS.lightBlue:COLORS.green)}>
            <MaterialCommunityIcons
            name='cellphone'
            size={20}
            color={COLORS.green}
            />
<WidthSpacer width={10}/>
            <TextInput
            secureTextEntry={obsecureText}
            placeholder='Enter Contact e.g 0726768809'
            onFocus={() => {setFieldTouched('contact_number')}}
            onBlur={() => {setFieldTouched('contact_number',"")}}
            autoCorrect={false}
            autoCapitalize='none'
            value={values.contact_number}
            onChangeText={handleChange('contact_number')}
            style={{flex:1}}
            />
            <TouchableOpacity onPress={() =>{
                setObsecureText(!obsecureText)
            }}>
                <MaterialCommunityIcons
                name={obsecureText?"eye-outline":"eye-off-outline"}
                size={18}
                color={COLORS.green}
                />
            </TouchableOpacity>
        </View>
        {touched.contact_number && errors.contact_number && (
            <Text style={styles.errorMessage}>
                {errors.contact_number}
            </Text>
        )}
        </View>
        </View>
        {isLoadingAddButton && <><HeightSpacer height={15}/><ReusableText text={`Please wait...`} align={"center"} family={'medium'} size={SIZES.small} color={COLORS.darkred}/></>}
        {isErrorAddButton && <><HeightSpacer height={15}/><ReusableText text={`${dataAddButton.message}`} align={"center"} family={'medium'} size={SIZES.small} color={COLORS.darkred}/></>}

        <HeightSpacer height={20}/>
        <ReusableBtn
onPress={handleSubmitValues.bind(null,values)}
btnText={"REGISTER"}
width={SIZES.width-50}
backgroundColor={COLORS.green}
borderColor={COLORS.green}
borderWidth={0}
textColor={COLORS.white}
radius={20}
        />
         
        
         <HeightSpacer height={15}/>
         <ReusableText text="Make sure all your details are matching those on the PDF." align={"center"} family={'medium'} size={SIZES.small} color={COLORS.green}/>
    </View>
)}
        </Formik>
    
    </View>
  )
}

export default Registration