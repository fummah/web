import { View, Text, TextInput, TouchableOpacity, Image, FlatList, ImageBackground } from 'react-native'
import React,{useState,useEffect} from 'react'
import { SafeAreaView } from 'react-native-safe-area-context';
import reusable from '../../components/Reusable/reusable.style';
import styles from './account.style';
import { COLORS,SIZES,FONTS } from '../../constants/theme';
import AppBar from '../../components/Reusable/AppBar'
import AccountTile from '../../components/Tiles/AccountTile';
import { HeightSpacer,ErrorAlert } from '../../components';
import useAxiosFetch from '../../hooks/use-axios';
import MyActivityIndicator from '../../components/Reusable/MyActivityIndicator';

const AccountStatement = () => {
  const [balance,setBalance] = useState('0.00');
  const [accounts,setAccounts] = useState([]);

  const {isLoading:isLoadingAccounts,isError:isErrorAccounts,data:dataAccounts,statusCode:statusCodeAccounts} = useAxiosFetch('getaccounts');

  useEffect(() => {
 
    if(dataAccounts && statusCodeAccounts===200)
    {
       setAccounts(dataAccounts.accounts);
       setBalance(dataAccounts.balance);       
    }
 },[dataAccounts]);

 if(isLoadingAccounts)
 {
    return (<MyActivityIndicator/>)
 }
 if(isErrorAccounts)
 {
    return (<ErrorAlert message={`${dataAccounts.message}`} onClose={()=>{}}/>)
 }

  const renderHeader = () =>{
    return(
       <View 
       style={{
          width:"100%",
          height:120,
          ...styles.shadow
       }}
       >
          <ImageBackground
          source={require('../../assets/1.png')}
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
<Text style={{color:COLORS.white,...FONTS.h3}}>Account Balance</Text>
<Text style={{marginTop:SIZES.base,color:COLORS.white,...FONTS.h1}}>R{balance}</Text>
<Text style={{color:COLORS.white,...FONTS.body5}}>Advance Payment</Text>
</View>
<View
style={{
 bottom:"-40%"
}}
>


  

 
</View>

             </ImageBackground>

       </View>
    )
 }
  return (
   <SafeAreaView style={reusable.container}>
    <View style={{height:50}}>
    <AppBar title={'Notifications'} color={COLORS.white}  icon={'home'} color1={COLORS.white}/>
    </View>
    {renderHeader()}
    <HeightSpacer height={15}/>
    <View style={styles.container}>
    {accounts.length<1 &&
 <Text style={{textAlign:'center',color:COLORS.green}}>No Transaction History</Text>
 }
    <FlatList
data={accounts}
keyExtractor={(item) => item.id}
showsVerticalScrollIndicator={false}
renderItem={({item}) => (
  <View style={styles.tile}>
  <AccountTile item={item} />
  </View>
)}
 />
</View>
 <HeightSpacer height={49}/>
   </SafeAreaView>
  )
}

export default AccountStatement