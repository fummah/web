import { Text, TextInput, TouchableOpacity, View } from 'react-native'
import React,{useState,useEffect} from 'react';
import styles from './signin.style';
import {Formik} from 'formik';
import * as Yup from 'yup';
import { COLORS, SIZES } from '../../constants/theme';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { ErrorAlert, HeightSpacer, ReusableBtn, ReusableText, WidthSpacer } from '../../components';
import useAxiosFetch from '../../hooks/use-axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useNavigation } from '@react-navigation/native';

const validationSchema = Yup.object().shape({

    member_id:Yup.string()
    .required('Required'),

    contactact_number:Yup.string()
    .min(5,"Invalid Password")
    .required('Required'),    
})

const setItemStorage = async (key, value) => {
    try {
      await AsyncStorage.setItem(key, value);
      return true;
    } catch (error) {
      return false;
    }
  };



const Signin = () => {
    const navigation = useNavigation();
    const [loginBtnClicked, setLoginBtnClicked] = useState(false);
    const [postData, setPostData] = useState({});
    const [obsecureText,setObsecureText] = useState(false)
  
    const { isLoading: isLoadingLogin, isError: isErrorLogin, data: dataLogin,statusCode: statusCodeLogin } = useAxiosFetch('login','POST',postData,loginBtnClicked);
  
  
    useEffect(() => {
      if(loginBtnClicked)
      {   
           
          console.log(dataLogin);
          setLoginBtnClicked(false);       
      }
      // eslint-disable-next-line react-hooks/exhaustive-deps
      }, [loginBtnClicked]);

    const handleSubmitValues = (values) => {
        setPostData(values); 
        console.log(values);
        setLoginBtnClicked(true);
    };
 

    if(dataLogin && statusCodeLogin===200)
    {
        const isset = setItemStorage('ACCEESS_GRANTED', dataLogin.token);
        if(isset)
        {
navigation.navigate("Bottom");
        }
        else{
return (<ErrorAlert message={'There is an error, try again.'}/>)
        }
    }
  
    return (
    <View style={styles.container}>
        <Formik
        initialValues={{member_id:"",contactact_number:""}}
        validationSchema={validationSchema}
        onSubmit={(value) =>{
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
    setFieldTouched
}) =>(
    <View style={{paddingTop:30}}>
        <View style={styles.wrapper}>
            <Text style={styles.label}>Member ID</Text>
            <View>
        <View style={styles.inputWrapper(touched.member_id ? COLORS.lightBlue:COLORS.green)}>
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
            placeholder='Enter Contact Number'
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
                color={COLORS.green}
                size={18}
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
        {isLoadingLogin && <><HeightSpacer height={15}/><ReusableText text={`Please wait...`} align={"center"} family={'medium'} size={SIZES.small} color={COLORS.darkred}/></>}

        {isErrorLogin && <><HeightSpacer height={15}/><ReusableText text={`${dataLogin.message}`} align={"center"} family={'medium'} size={SIZES.small} color={COLORS.darkred}/></>}

        <HeightSpacer height={20}/>
        <ReusableBtn
onPress={handleSubmitValues.bind(null,values)}
btnText={"SIGN IN"}
width={SIZES.width-50}
backgroundColor={COLORS.green}
borderColor={COLORS.green}
borderWidth={0}
textColor={COLORS.white}
radius={20}
        />
          </View>
)}
        </Formik>
    
    </View>
  )
}

export default Signin