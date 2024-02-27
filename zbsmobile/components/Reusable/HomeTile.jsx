import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import { COLORS, SIZES, FONTS } from '../../constants/theme'
import { MaterialCommunityIcons } from '@expo/vector-icons'

const HomeTile = ({icon,text1,text2,text3}) => {
  return (
   <TouchableOpacity 
   style={{
    width:"55%",
    paddingVertical:SIZES.padding,
    paddingHorizontal:SIZES.padding,
    marginLeft:10,
    marginRight:10,
    borderRadius:10,
    backgroundColor:COLORS.white
   }}
   >
<View style={{flexDirection:'row'}}>
<View style={styles.iconContainer}>
        <MaterialCommunityIcons name={icon} size={18} color={COLORS.white}/>
    </View>
    <View style={{marginLeft:SIZES.base}}>
 
<Text style={{...FONTS.h3}}>{text1}</Text>

    </View>
</View>
<View style={{marginTop:0}}>
<Text style={{...FONTS.h3,textAlign:"center",color:COLORS.gray}}>{text2}</Text>
<Text style={{color:COLORS.green,...FONTS.body5,textAlign:"center"}}>{text3}</Text>
</View>
   </TouchableOpacity>
  )
}

export default HomeTile

const styles = StyleSheet.create({
    iconContainer: {
        backgroundColor: COLORS.green,
        borderRadius: 100, // Make it round (half of the width and height)
        width: 25, // Adjust as needed
        height: 25, // Adjust as needed
        justifyContent: 'center',
        alignItems: 'center',
      }
})