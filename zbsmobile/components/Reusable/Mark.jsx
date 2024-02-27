import { StyleSheet, Text, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { MaterialCommunityIcons } from '@expo/vector-icons'
import WidthSpacer from './WidthSpacer'
import ReusableText from './ReusableText'
import { COLORS } from '../../constants/theme'

const Mark = ({status}) => {
  let colo = COLORS.green;
  let icon = "account-check";
  let txt = "Paid";
  if(status==="Waiting")
  {
    colo = COLORS.red;
    icon = "account-question";
    txt = "Waiting";
  }
  if (status==="unpaid") {
    colo = COLORS.darkred;
  icon = "account-remove";
  txt = "Unpaid";
  } 
  return (
    <View style={reusable.rowWidthSpace('flex-start')}>
      <MaterialCommunityIcons
name={icon}
size={20}
color={colo}
      />
      <WidthSpacer width={5}/>

      <ReusableText
text={txt}
family={'medium'}
size={15}
color={colo}
/>
    </View>
  )
}

export default Mark

const styles = StyleSheet.create({})