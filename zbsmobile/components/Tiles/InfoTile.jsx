import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from '../Reusable/reusable.style'
import { COLORS, TEXT } from '../../constants/theme'
import { AntDesign } from '@expo/vector-icons'
import WidthSpacer from '../Reusable/WidthSpacer'
import ReusableText from '../Reusable/ReusableText'

const InfoTile = ({title,title1,onPress}) => {
  return (
  <TouchableOpacity onPress={onPress} style={[reusable.rowWidthSpace('space-between'),styles.container]}>
<ReusableText text={title} family={"regular"} size={TEXT.small} color={COLORS.dark}/>
<View style={reusable.rowWidthSpace("flex-start")}>
<ReusableText text={title1} family={"regular"} size={TEXT.small} color={COLORS.gray}/>
<WidthSpacer width={5}/>
<AntDesign name='right' size={15} color={COLORS.black}/>
</View>
  </TouchableOpacity>
  )
}

export default InfoTile

const styles = StyleSheet.create({
  container:{
    borderBottomWidth:1,
    borderColor:COLORS.lightGrey,
    paddingVertical:10
  }
})