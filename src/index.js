// import { useState } from '@wordpress/element';
const { registerBlockType } = wp.blocks;
import { CheckboxControl, TextareaControl, RadioControl } from '@wordpress/components';
import { withState } from '@wordpress/compose';
const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow } = wp.components;
// https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-registration/
registerBlockType("ucsc-cdp/profile", {
	title: 'UCSC Profiles', 
	icon: 'screenoptions',
	category: "widgets",
	supports: {
		align: [ 'wide', 'full' ]
	},
	attributes: {
		uids: { type: 'string', default: '' },
		jpegPhoto: { type: 'boolean', default: false },
		cn: { type: 'boolean', default: false },
		title: { type: 'boolean', default: false },
		telephoneNumber: { type: 'boolean', default: false },
		mail: { type: 'boolean', default: false },
		labeledURI: { type: 'boolean', default: false },
		ucscPersonPubOfficeLocationDetail: { type: 'boolean', default: false },
		ucscPersonPubOfficeHours: { type: 'boolean', default: false },
		ucscPersonPubAreaOfExpertise: { type: 'boolean', default: false },
		ucscPersonPubDescription: { type: 'boolean', default: false },
		ucscPersonPubExpertiseReference: { type: 'boolean', default: false },
		ucscPersonPubResearchInterest: { type: 'boolean', default: false },
		ucscPersonPubTeachingInterest: { type: 'boolean', default: false },
		ucscPersonPubAwardsHonorsGrants: { type: 'boolean', default: false },
		ucscPersonPubSelectedPublication: { type: 'boolean', default: false },
		profLinks: { type: 'boolean', default: true },
		displayStyle: { type: 'string', default: 'grid' }
	},

	// https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/
	edit({attributes, setAttributes}) {
	const selectedDisplayOption = attributes.displayStyle;
	const DisplayRadioControl= ( props ) => {
		
		return (
			<RadioControl { ...props } onChange={(e) => setAttributes({displayStyle: e})} />
		);
	};
	return [
		<InspectorControls>
		<PanelBody title="People and Attributes">
		<TextareaControl 
		label="CruzID List"
		value={attributes.uids} 
		onChange={(e) => {
		        setAttributes({uids: e});
		}} 
		autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
		type="text" />
		<p>Displayed Attributes</p>
		<AttrCheckboxControl
		label="Photo"
		attrName="jpegPhoto"
		value={attributes.jpegPhoto}
		toggle={() => { setAttributes({jpegPhoto: !attributes.jpegPhoto})}}
		/>
		<AttrCheckboxControl
		label="Name"
		attrName="cn"
		value={attributes.cn}
		toggle={() => { setAttributes({cn: !attributes.cn})}}
		/>
		<AttrCheckboxControl
		label="Title"
		attrName="title"
		value={attributes.title}
		toggle={() => { setAttributes({title: !attributes.title})}}
		/>
		<AttrCheckboxControl
		label="Phone Number"
		attrName="telephoneNumber"
		value={attributes.telephoneNumber}
		toggle={() => { setAttributes({telephoneNumber: !attributes.telephoneNumber})}}
		/>
		<AttrCheckboxControl
		label="Email Address"
		attrName="mail"
		value={attributes.mail}
		toggle={() => { setAttributes({mail: !attributes.mail})}}
		/>
		<AttrCheckboxControl
		label="Website"
		attrName="labeledURI"
		value={attributes.labeledURI}
		toggle={() => { setAttributes({labeledURI: !attributes.labeledURI})}}
		/>
		<AttrCheckboxControl
		label="Office Location"
		attrName="ucscPersonPubOfficeLocationDetail"
		value={attributes.ucscPersonPubOfficeLocationDetail}
		toggle={() => { setAttributes({ucscPersonPubOfficeLocationDetail: !attributes.ucscPersonPubOfficeLocationDetail})}}
		/>
		<AttrCheckboxControl
		label="Office Hours"
		attrName="ucscPersonPubOfficeHours"
		value={attributes.ucscPersonPubOfficeHours}
		toggle={() => { setAttributes({ucscPersonPubOfficeHours: !attributes.ucscPersonPubOfficeHours})}}
		/>
		<AttrCheckboxControl
		label="Summary of Expertise"
		attrName="ucscPersonPubAreaOfExpertise"
		value={attributes.ucscPersonPubAreaOfExpertise}
		toggle={() => { setAttributes({ucscPersonPubAreaOfExpertise: !attributes.ucscPersonPubAreaOfExpertise})}}
		/>
		<AttrCheckboxControl
		label="Biography, Education and Training"
		attrName="ucscPersonPubDescription"
		value={attributes.ucscPersonPubDescription}
		toggle={() => { setAttributes({ucscPersonPubDescription: !attributes.ucscPersonPubDescription})}}
		/>
		<AttrCheckboxControl
		label="Areas of Expertise"
		attrName="ucscPersonPubExpertiseReference"
		value={attributes.ucscPersonPubExpertiseReference}
		toggle={() => { setAttributes({ucscPersonPubExpertiseReference: !attributes.ucscPersonPubExpertiseReference})}}
		/>
		<AttrCheckboxControl
		label="Research Interests"
		attrName="ucscPersonPubResearchInterest"
		value={attributes.ucscPersonPubResearchInterest}
		toggle={() => { setAttributes({ucscPersonPubResearchInterest: !attributes.ucscPersonPubResearchInterest})}}
		/>
		<AttrCheckboxControl
		label="Teaching Interests"
		attrName="ucscPersonPubTeachingInterest"
		value={attributes.ucscPersonPubTeachingInterest}
		toggle={() => { setAttributes({ucscPersonPubTeachingInterest: !attributes.ucscPersonPubTeachingInterest})}}
		/>
		<AttrCheckboxControl
		label="Awards, Honors and Grants"
		attrName="ucscPersonPubAwardsHonorsGrants"
		value={attributes.ucscPersonPubAwardsHonorsGrants}
		toggle={() => { setAttributes({ucscPersonPubAwardsHonorsGrants: !attributes.ucscPersonPubAwardsHonorsGrants})}}
		/>
		<AttrCheckboxControl
		label="Selected Publications"
		attrName="ucscPersonPubSelectedPublication"
		value={attributes.ucscPersonPubSelectedPublication}
		toggle={() => { setAttributes({ucscPersonPubSelectedPublication: !attributes.ucscPersonPubSelectedPublication})}}
		/>
		</PanelBody>
		<PanelBody title="Configuration">
		<DisplayRadioControl
		selected={selectedDisplayOption}
		label="Display Style"
		options={[
				{ label: 'Grid', value: 'grid' },
				{ label: 'List', value: 'list' }
		]}
		/>
		<AttrCheckboxControl
		label="Profile Links"
		attrName="profLinks"
		value={attributes.profLinks}
		toggle={() => { setAttributes({profLinks: !attributes.profLinks})}}
		/>
		</PanelBody>
		</InspectorControls>
		  , 
		  <h2>UCSC Profiles</h2>
		];

	},
	save({attributes}) {
		return null;
	}
});
function AttrCheckboxControl({attrName, label, value, toggle}) {
	return (
	<CheckboxControl
		  label={label}
		  checked={value}
		  onChange={() => toggle()}
	/>
	);
}

