import { StyleSheet, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { COLORS, SIZES } from '../../constants/theme'
import {HeightSpacer, ReusableText} from '../../components'
import { MaterialCommunityIcons } from '@expo/vector-icons'

const FuneralsTile = ({item}) => {
  return (
    <>
   <TouchableOpacity style={styles.container}>
    <View style={reusable.rowWidthSpace('flex-start')}>

<View>
    <View style={{flexDirection:"row"}}>
        <MaterialCommunityIcons name='account-settings-outline' color={COLORS.gray} size={20}/>
    <ReusableText text=" Person : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={`${item.first_name} ${item.last_name}`} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>
<HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name='credit-card-clock' color={COLORS.green} size={20}/>
    <ReusableText text=" Status : " family={'medium'} size={SIZES.medium} color={COLORS.green}/>
    <ReusableText text={item._type === "dependency"?"Dependent":"Owner"} family={'medium'} size={SIZES.medium} color={COLORS.green}/>
    </View>

    <HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name='map-marker' color={COLORS.gray} size={20}/>
    <ReusableText text=" Location : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={`${item.location_name}(${item.group_name})`} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>

    <HeightSpacer height={7}/>
    <View style={{flexDirection:"row"}}>
    <MaterialCommunityIcons name='alarm-multiple' color={COLORS.gray} size={20}/>
    <ReusableText text=" Entered At : " family={'medium'} size={SIZES.medium} color={COLORS.gray}/>
    <ReusableText text={item.date_entered} family={'medium'} size={SIZES.medium} color={COLORS.dark}/>
    </View>

</View>
    </View>
   </TouchableOpacity>
   <HeightSpacer height={20}/>
   </>
  )
}

export default FuneralsTile

const styles = StyleSheet.create({
    container:{
        padding:15,
        backgroundColor:COLORS.lightWhite,
        borderRadius:12,
        borderColor:COLORS.green,
        borderWidth:1
    }
})