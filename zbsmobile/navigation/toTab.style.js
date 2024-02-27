import { StyleSheet } from "react-native";
import { COLORS } from "../constants/theme";

const styles = StyleSheet.create({
profle:{
    position:"absolute",
    left:0,
    right:0,
    top:110,
    alignItem:"center"

},
image:{
    resizeMode:"cover",
    width:100,
    height:100,
    borderColor:COLORS.lightWhite,
    borderWidth:2
}
});

export default styles;