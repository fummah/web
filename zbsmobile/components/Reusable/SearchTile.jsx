import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import reusable from './reusable.style'
import { COLORS, SIZES } from '../../constants/theme'
import {HeightSpacer, Mark, NetworkImage, ReusableText, WidthSpacer} from '../../components'
import { MaterialCommunityIcons } from '@expo/vector-icons'

const SearchTile = ({item,onPress}) => {
  return (
   <TouchableOpacity style={styles.container} onPress={onPress}>
    <View style={reusable.rowWidthSpace('flex-start')}>
<MaterialCommunityIcons name='map-marker-account' size={50} color={COLORS.green}/>
<WidthSpacer width={15}/>

<View>
<ReusableText
text={item.member_name}
family={'medium'}
size={SIZES.medium}
color={COLORS.black}
/>

<HeightSpacer height={8}/>

<ReusableText
text={item.location}
family={'medium'}
size={14}
color={COLORS.gray}
/>

<HeightSpacer height={8}/>

<View style={reusable.rowWidthSpace('flex-start')}>

<View style={reusable.rowWidthSpace('flex-start')}>
      <MaterialCommunityIcons
name='file-phone-outline'
size={20}
color={COLORS.green}
      />
      <WidthSpacer width={5}/>

      <ReusableText
text={item.contact_number}
family={'medium'}
size={15}
color={COLORS.green}
/>
    </View>

<WidthSpacer width={5}/>

</View>
</View>
    </View>
   </TouchableOpacity>
  )
}

export default SearchTile

const styles = StyleSheet.create({
    container:{
        padding:10,
        backgroundColor:COLORS.lightWhite,
        borderRadius:12
    }
})