import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import { MaterialCommunityIcons } from '@expo/vector-icons'
import { COLORS, SIZES,FONTS } from '../../constants/theme'

const AlertTile = ({status,onPress}) => {
  return (
   <TouchableOpacity
   style={{
    flexDirection:"row",
    alignItems:"center",
    marginTop:25,
    marginHorizontal:SIZES.padding,
    paddingVertical:SIZES.radius,
    paddingHorizontal:8,
    backgroundColor:COLORS.white,
    borderRadius:SIZES.radius,
    ...styles.shadow
   }}
   onPress={onPress}
   >
    <MaterialCommunityIcons
name='bell'
size={34}
color={COLORS.green}
    />
<View style={{flex:1, marginLeft:SIZES.radius}}>
    <Text style={{...FONTS.h3}}>Funeral Status</Text>
    <Text style={{...FONTS.body4}}>ZBS Latest funeral is {status==="Open"?<Text style={{color:COLORS.darkred}}>Open</Text>:status}</Text>
</View>
<MaterialCommunityIcons
name='arrow-right-bold-circle'
size={24}
color={COLORS.green}
/>
    </TouchableOpacity>
  )
}

export default AlertTile

const styles = StyleSheet.create({
    shadow:{
        shadowColor:"#000",
        shadowOffset:{
            width:0,
            height:4
        }
    }
})