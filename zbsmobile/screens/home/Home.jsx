import { View, TouchableOpacity, ScrollView, ImageBackground,Text } from 'react-native'
import React,{useEffect,useState} from 'react';
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import { HeightSpacer,NetworkImage, HomeTile, AlertTile, HomeChart, ErrorAlert } from '../../components';
import styles from './home.style';
import { COLORS,SIZES,TEXT,FONTS } from '../../constants/theme';
import Latestfunerals from '../../components/Home/Latestfunerals';
import { useNavigation } from '@react-navigation/native';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';

const Home = () => {
const navigation = useNavigation();
const [details,setDetails] = useState({});
const [accumulated,setAccumulated] = useState({});
const [register,setRegister] = useState([]);
const [latest,setLatest] = useState({});
const [mydate,setMydat] = useState('');
const [graph1,setGraph1] = useState(['u']);
const [graph2,setGraph2] = useState([0]);
const {isLoading:isLoadingDetails,isError:isErrorDetails,data:dataDetails,statusCode:statusCodeDetails} = useAxiosFetch('getdetails');
useEffect(() => {
 
   if(dataDetails && statusCodeDetails===200)
   {
      setDetails(dataDetails.details);
      setAccumulated(dataDetails.accumulated);
      setRegister(dataDetails.allregister.register);
      setLatest(dataDetails.allregister.latest_funeral);
      setGraph1(dataDetails.graph.months);
      setGraph2(dataDetails.graph.values);
      setMydat(dataDetails.mydate);
   }
},[dataDetails]);

const onViewRegister = (member_id) => {
   console.log("clicked")
   navigation.navigate('Register', { member_id: member_id });
 };

if(isLoadingDetails)
{
   return (<MyActivityIndicator/>)
}
if(isErrorDetails)
{
   return (<ErrorAlert message={`${dataDetails.message}`} onClose={()=>{}}/>)
}
   const renderHeader = () =>{
      return(
         
         <View 
         style={{
            width:"100%",
            height:200,
            ...styles.shadow
         }}
         >
            
            <ImageBackground
            source={require('../../assets/2.png')}
            style={{
               flex:1,
               alignItems:"center",
               resizeMode:"cover"
            }}
            >

<View
style={{
   alignItems:"center",
   justifyContent:"center",
   marginTop:20

}}
>
<Text style={{color:COLORS.gray,...FONTS.h3}}>Accumulated Amount</Text>
<Text style={{marginTop:SIZES.base,color:COLORS.green,...FONTS.h1}}>R{accumulated.total_paid}</Text>
<Text style={{color:COLORS.dark,...FONTS.body5}}>R{latest.price} per funeral</Text>
</View>
<View
style={{
   position:"absolute",
   bottom:"-21%"
}}
>

    <View style={{flexDirection:"row",width:"80%"}}>
    <HomeTile icon={'cash-100'} text1={"Balance"} text2={`R${details.account_balance}`} text3={details.status}/>
    <HomeTile icon={'account-clock'} text1={"Funerals"} text2={`(${accumulated.total_funerals})`} text3={mydate}/>
    
    </View>
   
</View>

               </ImageBackground>

         </View>
      )
   }
  return (
   
   
      
     <SafeAreaView >
      <ScrollView>
     <View style={reusable.rowWidthSpace('space-between')}>
     
      <Text style={{color:COLORS.green,...FONTS.h2, padding:10,paddingLeft:15}}>{details.first_name} {details.last_name}</Text>

      <TouchableOpacity style={{
   width:35,
   height:35,
   alignItems:"center",
   justifyContent:"center",
   marginRight:15
}}>
<NetworkImage style={{
flex:1
}}
resizeMode="contain" 
source={"https://zbsburial.com/wp-content/uploads/2023/03/logo-120x63.jpg"} height={20} width={40}/>
</TouchableOpacity>
      </View>
      
         <View style={{flex:1,paddingBottom:90}}>
            
{renderHeader()}
<HeightSpacer height={30}/> 
<AlertTile status={latest.status} onPress={onViewRegister.bind(null, details.member_id)}/>

<Latestfunerals register={register}/>
<HeightSpacer height={5}/> 
<HomeChart graph1={graph1} graph2={graph2}/>

         </View>     
        
        <View>



        </View>
        </ScrollView>
     </SafeAreaView>
     
  
  )
}

export default Home