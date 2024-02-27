import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { COLORS, SIZES } from '../../constants/theme'
import {HeightSpacer, Mark, NetworkImage, ReusableText, WidthSpacer} from '../../components'
import { MaterialCommunityIcons } from '@expo/vector-icons'

const DependentsTile = ({item,onPress}) => {
  return (
   <TouchableOpacity style={styles.container} onPress={onPress}>
    <View style={reusable.rowWidthSpace('flex-start')}>
<MaterialCommunityIcons name='account-group-outline' size={40} color={COLORS.green}/>
<WidthSpacer width={15}/>

<View>
<ReusableText
text={`${item.first_name} ${item.surname}`}
family={'medium'}
size={SIZES.medium}
color={COLORS.black}
/>

<HeightSpacer height={8}/>

<ReusableText
text={item.status.toUpperCase()}
family={'medium'}
size={14}
color={COLORS.gray}
/>

<HeightSpacer height={8}/>

<View style={reusable.rowWidthSpace('flex-start')}>


<WidthSpacer width={5}/>

</View>
</View>
    </View>
   </TouchableOpacity>
  )
}

export default DependentsTile

const styles = StyleSheet.create({
    container:{
        padding:10,
        backgroundColor:COLORS.lightWhite,
        borderRadius:12
    }
})