import { View, Text, TextInput, TouchableOpacity, Image, FlatList } from 'react-native'
import React,{useState,useEffect} from 'react'
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import styles from './register.style';
import { COLORS,SIZES } from '../../constants/theme';
import AppBar from '../../components/Reusable/AppBar'
import { HeightSpacer, ReusableText,ErrorAlert } from '../../components';
import RegisterTile from '../../components/Reusable/RegisterTile';
import { useRoute } from '@react-navigation/native';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';

const Register = () => {
  const [register,setRegister] = useState([]);
  const [fullname,setFullname] = useState('');
  const [contactnumber,setContactnumber] = useState('loading');
  const route = useRoute();
  const { member_id} = route.params;
  const {isLoading:isLoadingRegister,isError:isErrorRegister,data:dataRegister,statusCode:statusCodeRegister} = useAxiosFetch(`getregister/${member_id}`);
  useEffect(() => {
 
    if(dataRegister && statusCodeRegister===200)
    {
       setRegister(dataRegister.register.register);
       setFullname(dataRegister.full_name);
       setContactnumber(dataRegister.contact_number);
    }
 },[dataRegister]);

 if(isLoadingRegister)
{
   return (<MyActivityIndicator/>)
}
if(isErrorRegister)
{
   return (<ErrorAlert message={`${dataRegister.message}`} onClose={()=>{}}/>)
}
  
  
  return (
   <SafeAreaView style={reusable.container}>
    <View style={{height:50}}>
    <AppBar title={'Notifications'} color={COLORS.white}  icon={'home'} color1={COLORS.white}/>
    </View>
    {!isLoadingRegister &&
<View style={styles.searcContainer}>
<HeightSpacer height={10}/>
<Text style={{color:COLORS.red}}>{`MEMBER ID: ${member_id}`}</Text>
    <HeightSpacer height={12}/>
<ReusableText
text={`${fullname} (${contactnumber})`}
family={'medium'}
size={SIZES.medium}
color={COLORS.green}
/>
<HeightSpacer height={20}/>
</View>
}
 <FlatList
data={register}
keyExtractor={(item) => item.register_id}
showsVerticalScrollIndicator={false}
renderItem={({item}) => (
  <View style={styles.tile}>
  <RegisterTile item={item} />
  </View>
)}
 />

   </SafeAreaView>
  )
}

export default Register