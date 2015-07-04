


function toPlainText(s){
	s = s.replace (/&/g, "&amp");
	s = s.replace (/</g, "&lt");
	s = s.replace (/>/g, "&gt");
	return s;
}