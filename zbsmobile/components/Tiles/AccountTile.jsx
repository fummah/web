import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from '../Reusable/reusable.style'
import { COLORS, TEXT } from '../../constants/theme'
import { AntDesign } from '@expo/vector-icons'
import WidthSpacer from '../Reusable/WidthSpacer'
import ReusableText from '../Reusable/ReusableText'

const AccountTile = ({item,onPress}) => {
  return (
  <TouchableOpacity onPress={onPress} style={[reusable.rowWidthSpace('space-between'),styles.container]}>
    <View>
    <ReusableText text={item.date_entered} family={"regular"} size={TEXT.small} color={COLORS.gray}/>
<ReusableText text={item.transaction_name} family={"regular"} size={TEXT.medium} color={COLORS.dark}/>

</View>
<View style={reusable.rowWidthSpace("flex-start")}>
<ReusableText text={item.amount} family={"regular"} size={TEXT.small} color={COLORS.gray}/>
<WidthSpacer width={5}/>
<AntDesign name='right' size={15} color={COLORS.green}/>
</View>
  </TouchableOpacity>
  )
}

export default AccountTile

const styles = StyleSheet.create({
  container:{
    borderBottomWidth:1,
    borderColor:COLORS.lightGrey,
    paddingVertical:10,
    width:"100%",
  }
})