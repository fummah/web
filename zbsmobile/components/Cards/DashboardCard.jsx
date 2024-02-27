import { StyleSheet, Text, TouchableOpacity, View } from 'react-native'
import React from 'react'
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { COLORS,SIZES } from '../../constants/theme';
import ReusableText from '../Reusable/ReusableText';
import HeightSpacer from '../Reusable/HeightSpacer';

const DashboardCard = ({title,firstText,secondText,firstColor,secondColor,icon,bgcolor}) => {
    return (
       
        <View style={styles.card(bgcolor)}>
          <View>
            <View style={styles.header}>
            <ReusableText
text={title}
family={'medium'}
size={SIZES.medium}
color={COLORS.dark}
/>
              <View style={styles.iconContainer}>
            <Icon name={icon} size={24} color="white" />
          </View>
            </View>          

<ReusableText
text={firstText}
family={'medium'}
size={14}
color={firstColor}
/>
<HeightSpacer height={5}/>
<ReusableText
text={secondText}
family={'medium'}
size={14}
color={secondColor}
/>
          </View>
        </View>
      );
}

export default DashboardCard
const styles = StyleSheet.create({
    card: (bgcolor) => ({
      margin: 0,
      elevation: 0,
      padding:20,
        backgroundColor:bgcolor,
        borderRadius:30,
    }),
    header: {
      flexDirection: 'row',
      justifyContent: 'space-between',
      alignItems: 'center',
    },
    title: {
      fontSize: 20,
      fontWeight: 'bold',
    },
    text: {
      marginTop: 10,
      fontSize: 16,
      lineHeight: 24,
    },
    iconContainer: {
      backgroundColor: 'green',
      borderRadius: 100, // Make it round (half of the width and height)
      width: 36, // Adjust as needed
      height: 36, // Adjust as needed
      justifyContent: 'center',
      alignItems: 'center',
    },
  });