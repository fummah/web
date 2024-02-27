import { Dimensions } from 'react-native'
const { height, width } = Dimensions.get('window');

const COLORS = {
    blue: "#4267B2",
    darkred:"red",
    red: "#EB6A58",
    green: "#449282",
    white: "#FBFBFB",
    lightWhite: "#FFFFFF",
    lightBlue: "#6885C1",
    lightRed: "#EB9C9B",
    lightGreen: "#73ADA1",
    black: '#121212',
    dark: '#3D3A45',
    gray: '#8C8896',
    lightGrey: '#D1CFD5',
    yellow: '#f3b606',
    darkgreen:'green',
    gold:'gold',
};


const SIZES = {
    xSmall: 10,
    small: 12,
    medium: 16,
    large: 20,
    xLarge: 24,
    xxLarge: 44,
     // global sizes
     base: 8,
     font: 14,
     radius: 12,
     padding: 24,
 
     // font sizes
     h1: 30,
     h2: 22,
     h3: 16,
     h4: 14,
     body1: 30,
     body2: 22,
     body3: 16,
     body4: 14,
     body5: 12,
 
    height,
    width
};

const TEXT = {
    xxSmall: 11,
    xSmall: 13,
    small: 15,
    medium: 17,
    large: 21,
    xLarge: 27,
    xxLarge: 32,
};


const SHADOWS = {
    small: {
        shadowColor: "#000",
        shadowOffset: {
            width: 0,
            height: 2,
        },
        shadowOpacity: 0.25,
        shadowRadius: 3.84,
        elevation: 2,
    },
    medium: {
        shadowColor: "#000",
        shadowOffset: {
            width: 0,
            height: 2,
        },
        shadowOpacity: 0.25,
        shadowRadius: 5.84,
        elevation: 5,
    },
};
const FONTS = {
    h1: { fontFamily: "medium", fontSize: SIZES.h1, lineHeight: 36 },
    h2: { fontFamily: "medium", fontSize: SIZES.h2, lineHeight: 30 },
    h3: { fontFamily: "medium", fontSize: SIZES.h3, lineHeight: 22 },
    h4: { fontFamily: "medium", fontSize: SIZES.h4, lineHeight: 22 },
    body1: { fontFamily: "regular", fontSize: SIZES.body1, lineHeight: 36 },
    body2: { fontFamily: "regular", fontSize: SIZES.body2, lineHeight: 30 },
    body3: { fontFamily: "regular", fontSize: SIZES.body3, lineHeight: 22 },
    body4: { fontFamily: "regular", fontSize: SIZES.body4, lineHeight: 22 },
    body5: { fontFamily: "regular", fontSize: SIZES.body5, lineHeight: 22 },
};


export { COLORS, SIZES, SHADOWS, TEXT, FONTS };