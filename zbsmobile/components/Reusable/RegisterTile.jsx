import { StyleSheet, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { COLORS, SIZES } from '../../constants/theme'
import {HeightSpacer, ReusableText} from '../../components'
import { MaterialCommunityIcons } from '@expo/vector-icons'
import { useNavigation } from '@react-navigation/native'

const RegisterTile = ({item}) => {
    const navigation = useNavigation();
    const goDetails = (funeral_id) =>{
navigation.navigate('FuneralDetails',{funeral_id:funeral_id});
    }
    let colo = COLORS.green;
  let icon = "account-check";
  let txt = "Paid";
  if(item.status==="Waiting")
  {
    colo = COLORS.red;
    icon = "account-question";
    txt = "Waiting";
  }
  if (item.status==="unpaid") {
    colo = COLORS.darkred;
  icon = "account-remove";
  txt = "Unpaid";
  } 
  return (
    <>
   <TouchableOpacity style={styles.container} onPress={goDetails.bind(null, item.funeral_id)}>
    <View style={reusable.rowWidthSpace('flex-start')}>

<View>
    <View style={{flexDirection:"row"}}>
        <MaterialCommunityIcons name='account-settings-outline' color={COLORS.gray} size={20}/>
    <ReusableText text=" Funeral Name : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={item.funeral_name} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>
<HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name={icon} color={colo} size={20}/>
    <ReusableText text=" Payment Status : " family={'medium'} size={SIZES.medium} color={colo}/>
    <ReusableText text={txt} family={'medium'} size={SIZES.medium} color={colo}/>
    </View>

    <HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name='account-star' color={COLORS.gray} size={20}/>
    <ReusableText text=" Entered By : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={item.entered_by} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>

    <HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name='alarm-multiple' color={COLORS.gray} size={20}/>
    <ReusableText text=" Entered At : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={item.date_entered} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>

    <HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name='cash' color={COLORS.gray} size={20}/>
    <ReusableText text=" Amount Charged : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={item.amount_paid} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>
</View>
    </View>
   </TouchableOpacity>
   <HeightSpacer height={20}/>
   </>
  )
}

export default RegisterTile

const styles = StyleSheet.create({
    container:{
        padding:15,
        backgroundColor:COLORS.lightWhite,
        borderRadius:12,
        borderColor:COLORS.green,
        borderWidth:1
    }
})