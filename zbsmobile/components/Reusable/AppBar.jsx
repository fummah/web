import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { AntDesign } from '@expo/vector-icons'
import NetworkImage from './NetworkImage'
import { useNavigation } from '@react-navigation/native'
import { COLORS } from '../../constants/theme'


const AppBar = ({color,color1,title,icon}) => {
   const navigation = useNavigation();
  return (
    <View style={styles.overlay}>
      <View style={reusable.rowWidthSpace('space-between')}>
        <TouchableOpacity style={styles.box(color)} onPress={() => navigation.goBack()}>
<AntDesign
name='left'
size={26}
color={COLORS.green}
/>
        </TouchableOpacity>
        <NetworkImage source={"https://zbsburial.com/wp-content/uploads/2023/03/logo-120x63.jpg"} height={20} width={40}/>
<TouchableOpacity style={styles.box(color1)} onPress={() => navigation.navigate("Bottom")}>
<AntDesign
name={icon}
size={26}

color={COLORS.green}
/>
</TouchableOpacity>
      </View>
    </View>
  )
}

export default AppBar

const styles = StyleSheet.create({
    overlay:{
        position:"absolute",
        top:8,
        left:0,
        right:0,
        justifyContent:"center"
    },
    box:(color) => ({
        backgroundColor:color,
        width:30,
        height:30,
        borderRadius:9,
        alignItems:"center",
        justifyContent:"center"

    }),
    box1:(color1) => ({
        backgroundColor:color,
        width:30,
        height:30,
        borderRadius:9,
        alignItems:"center",
        justifyContent:"center"

    })
})