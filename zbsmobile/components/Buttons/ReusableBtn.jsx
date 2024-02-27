import { StyleSheet, TouchableOpacity, Text } from 'react-native';
import React from 'react';
import { SIZES } from '../../constants/theme';
import Icon from 'react-native-vector-icons/FontAwesome';

const ReusableBtn = ({onPress,btnText,textColor,width,backgroundColor,borderWidth,borderColor,icon,radius}) => {
  return (
   <TouchableOpacity onPress={onPress} style={styles.btnStyle(width,backgroundColor,borderWidth,borderColor,radius)}>
    

    <Text style={styles.btnText(textColor)}><Icon name={icon} size={SIZES.medium} color={textColor} /> {btnText}</Text>
   </TouchableOpacity>
  )
}

export default ReusableBtn;

const styles = StyleSheet.create({
    btnText: (textColor) => ({
        fontFamily:"medium",
        fontSize:SIZES.medium,
        color: textColor
    }),
    btnStyle: (width,backgroundColor,borderWidth,borderColor,radius) => ({
        width:width,
        backgroundColor:backgroundColor,
        alignItems:"center",
        justifyContent: "center",
        height:45,
        borderRadius: radius,
        borderColor:borderColor,
        borderWidth:borderWidth
    })
});