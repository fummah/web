import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { COLORS, FONTS } from '../../constants/theme'
import {HeightSpacer,ReusableText, WidthSpacer} from '../../components'
import { FontAwesome5 } from '@expo/vector-icons'

const NotificationTile = ({item,onPress}) => {
  return (
    <>
   <TouchableOpacity style={styles.container1} onPress={onPress}>
    <View style={reusable.rowWidthSpace('flex-start')}>
      <View style={styles.iconContainer}>
        <FontAwesome5 name='comment' size={20} color={COLORS.green} />
      </View>
      <View style={styles.messageContainer}>
        <Text style={{color:COLORS.dark,...FONTS.h3}}>{item.title}</Text>
        <HeightSpacer height={5}/>
        <Text style={{color:COLORS.gray,...FONTS.body3}}>{item.message}</Text>
        <HeightSpacer height={10}/>
        <Text style={styles.datetimeText}>{item.date_entered}</Text>
      </View>
      
    </View>
    </TouchableOpacity>
    <HeightSpacer height={12}/>
   </>
  )
}

export default NotificationTile

const styles = StyleSheet.create({
    container1:{
        padding:10,
        backgroundColor:COLORS.lightWhite,
        borderRadius:20,
        borderBottomLeftRadius:0
    },
          iconContainer: {
        marginRight: 10,
        justifyContent: 'center',
      },
      messageContainer: {
        flex: 1,
      },
      messageText: {
        fontSize: 16,
        marginBottom: 5,
        color:COLORS.black
      },
      datetimeText: {
        fontSize: 12,
        color: COLORS.green
      },
})