import { View, Text, ScrollView } from 'react-native'
import React,{useState,useEffect} from 'react'
import { COLORS, SIZES } from '../../constants/theme'
import { HeightSpacer, InfoTile, ReusableText,ErrorAlert, ReusableBtn } from '../../components'
import Places from '../../components/Home/Places'
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';
import { useNavigation } from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';

const Details = () => {
  const navigation = useNavigation();
  const [details,setDetails] = useState({});
  const [locations,setLocations] = useState([]);
  const [mydate,setMyDate] = useState('-');

  const {isLoading:isLoadingDetails,isError:isErroDetails,data:dataDetails,statusCode:statusDetails} = useAxiosFetch('getprofile');

  useEffect(() => {
 
    if(dataDetails && statusDetails===200)
    {
      setDetails(dataDetails.details);      
      setLocations(dataDetails.locations);     
      setMyDate(dataDetails.date_registered);     
    }
 },[dataDetails]);

 if(isLoadingDetails)
 {
    return (<MyActivityIndicator/>)
 }
 if(isErroDetails)
 {
    return (<ErrorAlert message={`${dataDetails.message}`} onClose={()=>{}}/>)
 }
 
 const handleSignout = async () =>{
  await AsyncStorage.removeItem("ACCEESS_GRANTED");
  navigation.navigate("Onboard");
 }

  return (
    <ScrollView style={{flex: 1}}>
    <View style={{backgroundColor:COLORS.lightWhite,flex:1}}>
        <View style={{marginHorizontal:20}}>
        <HeightSpacer height={20}/>
            <ReusableText text={'Account Details'} family={"regular"} size={SIZES.xLarge-5} color={COLORS.green}/>
<HeightSpacer height={10}/>
<InfoTile title={'Full Name'} title1={`${details.first_name} ${details.last_name}`}/>
<HeightSpacer height={3}/>
<InfoTile title={'Date Registered'} title1={`${mydate}`}/>
<HeightSpacer height={3}/>
<InfoTile title={'Phone Number'} title1={`${details.contact_number}`}/>
<HeightSpacer height={3}/>
<InfoTile title={'Member ID'} title1={`${details.member_id}`}/>
<HeightSpacer height={15}/>


<ReusableText text={'Support'} family={"regular"} size={SIZES.xLarge-5} color={COLORS.green}/>
<HeightSpacer height={10}/>
<InfoTile title={'Get Help'} title1={'+27 82 876 9087'}/>
<HeightSpacer height={15}/>

<ReusableText text={'Legal'} family={"regular"} size={SIZES.xLarge-5} color={COLORS.green}/>
<HeightSpacer height={10}/>
<InfoTile title={'Terms & Conditions'} title1={'ZBS Constitution'}/>
<HeightSpacer height={3}/>
<InfoTile title={'Privacy Policy'} title1={'Policy'}/>
<HeightSpacer height={15}/>
<ReusableText text={'Branches'} family={"regular"} size={SIZES.xLarge-5} color={COLORS.green}/>
<HeightSpacer height={10}/>
<Places locations={locations}/>

<HeightSpacer height={25}/>
<ReusableBtn 
onPress={handleSignout}
btnText={"Signout"}
width={(SIZES.width-50)/2.2}
backgroundColor={COLORS.red}
borderColor={COLORS.darkred}
textColor={COLORS.white}
icon={"user"}
borderWidth={0}
radius={50}
/>
<HeightSpacer height={150}/>

        </View>
     
    </View>
    </ScrollView>
  )
}

export default Details