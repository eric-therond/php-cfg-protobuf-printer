<?php

declare(strict_types=1);

/**
 * This file is part of PHP-CFG-PROTOBUF-PRINTER
 *
 * @copyright 2026 Eric Therond. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */

namespace ProtobufPrinter;

use LogicException;
use PHPCfg\Func;
use PHPCfg\Script;
use PHPCfg\Operand;
use PHPCfg\Printer\Printer;
use ProtobufPrinter\ProtobufGenerated\ScalarType as PBScalarType;
use ProtobufPrinter\ProtobufGenerated\OpKind as PBOpKind;
use ProtobufPrinter\ProtobufGenerated\Scalar as PBScalar;
use ProtobufPrinter\ProtobufGenerated\Script as PBScript;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block as PBBlock;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Op as PBOp;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\CatchTarget as PBCatchTarget;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\FinallyTarget as PBFinallyTarget;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Operand\PBNull;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Operand\Literal as PBLiteral;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Operand\Temporary as PBTemporary;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Operand\Variable as PBVariable;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Operand\OneOperand as PBOneOperand;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Op\MapLabel as PBMapLabel;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Op\OneLabelType as PBOneLabelType;
use ProtobufPrinter\ProtobufGenerated\Script\PBFunction\Block\Op\ChildBlock as PBChildBlock;
use Google\Protobuf\Internal\GPBUtil;

class Protobuf extends Printer
{
    private static function getEnumKindFromString(String $opkind): int
    {
        switch ($opkind) {
            case "Phi":
                return PBOpKind::Phi;
            case "Expr_Throw":
                return PBOpKind::Expr_Throw;
            case "Expr_Query":
                return PBOpKind::Expr_Query;
            case "Expr_Stackalloc":
                return PBOpKind::Expr_Stackalloc;
            case "Expr_Sizeof":
                return PBOpKind::Expr_Sizeof;
            case "Expr_IsPattern":
                return PBOpKind::Expr_IsPattern;
            case "Expr_ConstFetch":
                return PBOpKind::Expr_ConstFetch;
            case "Expr_AddressOfExpression":
                return PBOpKind::Expr_AddressOfExpression;
            case "Expr_Range":
                return PBOpKind::Expr_Range;
            case "Expr_IndexExpression":
                return PBOpKind::Expr_IndexExpression;
            case "Expr_Tuple":
                return PBOpKind::Expr_Tuple;
            case "Expr_Parenthesized":
                return PBOpKind::Expr_Parenthesized;
            case "Expr_InterpolatedString":
                return PBOpKind::Expr_InterpolatedString;
            case "Expr_Regex":
                return PBOpKind::Expr_Regex;
            case "Expr_Empty":
                return PBOpKind::Expr_Empty;
            case "Expr_Spread":
                return PBOpKind::Expr_Spread;
            case "Expr_ArrayBinding":
                return PBOpKind::Expr_ArrayBinding;
            case "Expr_ObjectBinding":
                return PBOpKind::Expr_ObjectBinding;
            case "Expr_ClassExpr":
                return PBOpKind::Expr_ClassExpr;
            case "Expr_Continue":
                return PBOpKind::Expr_Continue;
            case "Expr_Delete":
                return PBOpKind::Expr_Delete;
            case "Expr_Void":
                return PBOpKind::Expr_Void;
            case "Expr_Typeof":
                return PBOpKind::Expr_Typeof;
            case "Expr_Star":
                return PBOpKind::Expr_Star;
            case "Expr_Starred":
                return PBOpKind::Expr_Starred;
            case "Expr_StaticCall":
                return PBOpKind::Expr_StaticCall;
            case "Expr_StaticPropertyFetch":
                return PBOpKind::Expr_StaticPropertyFetch;
            case "Expr_BinaryOp_As":
                return PBOpKind::Expr_BinaryOp_As;
            case "Expr_BinaryOp_Comma":
                return PBOpKind::Expr_BinaryOp_Comma;
            case "Expr_BinaryOp_First":
                return PBOpKind::Expr_BinaryOp_First;
            case "Expr_BinaryOp_In":
                return PBOpKind::Expr_BinaryOp_In;
            case "Expr_BinaryOp_Is":
                return PBOpKind::Expr_BinaryOp_Is;
            case "Expr_BinaryOp_IsNot":
                return PBOpKind::Expr_BinaryOp_IsNot;
            case "Expr_BinaryOp_NotIn":
                return PBOpKind::Expr_BinaryOp_NotIn;
            case "Expr_Slice":
                return PBOpKind::Expr_Slice;
            case "Expr_KeyVar":
                return PBOpKind::Expr_KeyVar;
            case "Expr_Closure":
                return PBOpKind::Expr_Closure;
            case "Expr_CommandParam":
                return PBOpKind::Expr_CommandParam;
            case "Expr_Switch":
                return PBOpKind::Expr_Switch;
            case "Expr_SuppressNullableWarningExpression":
                return PBOpKind::Expr_SuppressNullableWarningExpression;
            case "Expr_Checked":
                return PBOpKind::Expr_Checked;
            case "Expr_Discard":
                return PBOpKind::Expr_Discard;
            case "Expr_Binding":
                return PBOpKind::Expr_Binding;
            case "Expr_With":
                return PBOpKind::Expr_With;
            case "Expr_Array":
                return PBOpKind::Expr_Array;
            case "Expr_ArrayDimFetch":
                return PBOpKind::Expr_ArrayDimFetch;
            case "Expr_ArrowFunction":
                return PBOpKind::Expr_ArrowFunction;
            case "Expr_Assign":
                return PBOpKind::Expr_Assign;
            case "Expr_AssignRef":
                return PBOpKind::Expr_AssignRef;
            case "Expr_Await":
                return PBOpKind::Expr_Await;
            case "Expr_BitwiseNot":
                return PBOpKind::Expr_BitwiseNot;
            case "Expr_BooleanNot":
                return PBOpKind::Expr_BooleanNot;
            case "Expr_ConcatList":
                return PBOpKind::Expr_ConcatList;
            case "Expr_FuncCall":
                return PBOpKind::Expr_FuncCall;
            case "Expr_InstanceOf":
                return PBOpKind::Expr_InstanceOf;
            case "Expr_MethodCall":
                return PBOpKind::Expr_MethodCall;
            case "Expr_New":
                return PBOpKind::Expr_New;
            case "Expr_Param":
                return PBOpKind::Expr_Param;
            case "Expr_PropertyFetch":
                return PBOpKind::Expr_PropertyFetch;
            case "Expr_Unary":
                return PBOpKind::Expr_Unary;
            case "Expr_UnaryMinus":
                return PBOpKind::Expr_UnaryMinus;
            case "Expr_UnaryPlus":
                return PBOpKind::Expr_UnaryPlus;
            case "Expr_Yield":
                return PBOpKind::Expr_Yield;
            case "Expr_Isset":
                return PBOpKind::Expr_Isset;
            case "Expr_NsFuncCall":
                return PBOpKind::Expr_NsFuncCall;
            case "Expr_Print":
                return PBOpKind::Expr_Print;
            case "Expr_Assertion":
                return PBOpKind::Expr_Assertion;
            case "Expr_VarVar":
                return PBOpKind::Expr_VarVar;
            case "Expr_ClassConstFetch":
                return PBOpKind::Expr_ClassConstFetch;
            case "Expr_Include":
                return PBOpKind::Expr_Include;
            case "Expr_Eval":
                return PBOpKind::Expr_Eval;
            case "Expr_YieldFrom":
                return PBOpKind::Expr_YieldFrom;
            case "Expr_AssignOp_BitwiseAnd":
                return PBOpKind::Expr_AssignOp_BitwiseAnd;
            case "Expr_AssignOp_BitwiseOr":
                return PBOpKind::Expr_AssignOp_BitwiseOr;
            case "Expr_AssignOp_Comma":
                return PBOpKind::Expr_AssignOp_Comma;
            case "Expr_BinaryOp_BooleanOr":
                return PBOpKind::Expr_BinaryOp_BooleanOr;
            case "Expr_BinaryOp_BooleanAnd":
                return PBOpKind::Expr_BinaryOp_BooleanAnd;
            case "Expr_BinaryOp_BitwiseAnd":
                return PBOpKind::Expr_BinaryOp_BitwiseAnd;
            case "Expr_BinaryOp_BitwiseOr":
                return PBOpKind::Expr_BinaryOp_BitwiseOr;
            case "Expr_BinaryOp_BitwiseXor":
                return PBOpKind::Expr_BinaryOp_BitwiseXor;
            case "Expr_BinaryOp_BitwiseNot":
                return PBOpKind::Expr_BinaryOp_BitwiseNot;
            case "Expr_BinaryOp_BitwiseNotAnd":
                return PBOpKind::Expr_BinaryOp_BitwiseNotAnd;
            case "Expr_BinaryOp_UnsignedRightShift":
                return PBOpKind::Expr_BinaryOp_UnsignedRightShift;
            case "Expr_BinaryOp_Coalesce":
                return PBOpKind::Expr_BinaryOp_Coalesce;
            case "Expr_BinaryOp_Concat":
                return PBOpKind::Expr_BinaryOp_Concat;
            case "Expr_BinaryOp_Div":
                return PBOpKind::Expr_BinaryOp_Div;
            case "Expr_BinaryOp_Equal":
                return PBOpKind::Expr_BinaryOp_Equal;
            case "Expr_BinaryOp_Greater":
                return PBOpKind::Expr_BinaryOp_Greater;
            case "Expr_BinaryOp_GreaterOrEqual":
                return PBOpKind::Expr_BinaryOp_GreaterOrEqual;
            case "Expr_BinaryOp_Identical":
                return PBOpKind::Expr_BinaryOp_Identical;
            case "Expr_BinaryOp_LogicalXor":
                return PBOpKind::Expr_BinaryOp_LogicalXor;
            case "Expr_BinaryOp_Minus":
                return PBOpKind::Expr_BinaryOp_Minus;
            case "Expr_BinaryOp_Mod":
                return PBOpKind::Expr_BinaryOp_Mod;
            case "Expr_BinaryOp_Mul":
                return PBOpKind::Expr_BinaryOp_Mul;
            case "Expr_BinaryOp_NotEqual":
                return PBOpKind::Expr_BinaryOp_NotEqual;
            case "Expr_BinaryOp_NotIdentical":
                return PBOpKind::Expr_BinaryOp_NotIdentical;
            case "Expr_BinaryOp_Plus":
                return PBOpKind::Expr_BinaryOp_Plus;
            case "Expr_BinaryOp_Pow":
                return PBOpKind::Expr_BinaryOp_Pow;
            case "Expr_BinaryOp_ShiftLeft":
                return PBOpKind::Expr_BinaryOp_ShiftLeft;
            case "Expr_BinaryOp_ShiftRight":
                return PBOpKind::Expr_BinaryOp_ShiftRight;
            case "Expr_BinaryOp_Smaller":
                return PBOpKind::Expr_BinaryOp_Smaller;
            case "Expr_BinaryOp_SmallerOrEqual":
                return PBOpKind::Expr_BinaryOp_SmallerOrEqual;
            case "Expr_BinaryOp_Spaceship":
                return PBOpKind::Expr_BinaryOp_Spaceship;
            case "Expr_BinaryOp_Various":
                return PBOpKind::Expr_BinaryOp_Various;
            case "Expr_Cast_Array":
                return PBOpKind::Expr_Cast_Array;
            case "Expr_Cast_Bool":
                return PBOpKind::Expr_Cast_Bool;
            case "Expr_Cast_Double":
                return PBOpKind::Expr_Cast_Double;
            case "Expr_Cast_Int":
                return PBOpKind::Expr_Cast_Int;
            case "Expr_Cast_Object":
                return PBOpKind::Expr_Cast_Object;
            case "Expr_Cast_String":
                return PBOpKind::Expr_Cast_String;
            case "Expr_Cast_Type":
                return PBOpKind::Expr_Cast_Type;
            case "Expr_Cast_Unset":
                return PBOpKind::Expr_Cast_Unset;
            case "Iterator_Key":
                return PBOpKind::Iterator_Key;
            case "Iterator_Next":
                return PBOpKind::Iterator_Next;
            case "Iterator_Reset":
                return PBOpKind::Iterator_Reset;
            case "Iterator_Valid":
                return PBOpKind::Iterator_Valid;
            case "Iterator_Value":
                return PBOpKind::Iterator_Value;
            case "Stmt_Class":
                return PBOpKind::Stmt_Class;
            case "Stmt_ClassMethod":
                return PBOpKind::Stmt_ClassMethod;
            case "Stmt_Function":
                return PBOpKind::Stmt_Function;
            case "Stmt_Import":
                return PBOpKind::Stmt_Import;
            case "Stmt_Jump":
                return PBOpKind::Stmt_Jump;
            case "Stmt_JumpIf":
                return PBOpKind::Stmt_JumpIf;
            case "Stmt_Use":
                return PBOpKind::Stmt_Use;
            case "Stmt_Namespace":
                return PBOpKind::Stmt_Namespace;
            case "Stmt_Property":
                return PBOpKind::Stmt_Property;
            case "Stmt_Switch":
                return PBOpKind::Stmt_Switch;
            case "Stmt_Try":
                return PBOpKind::Stmt_Try;
            case "Stmt_VarDeclaration":
                return PBOpKind::Stmt_VarDeclaration;
            case "Stmt_With":
                return PBOpKind::Stmt_With;
            case "Stmt_Yield":
                return PBOpKind::Stmt_Yield;
            case "Stmt_Break":
                return PBOpKind::Stmt_Break;
            case "Stmt_Interface":
                return PBOpKind::Stmt_Interface;
            case "Stmt_Export":
                return PBOpKind::Stmt_Export;
            case "Stmt_Trait":
                return PBOpKind::Stmt_Trait;
            case "Stmt_TraitUse":
                return PBOpKind::Stmt_TraitUse;
            case "Stmt_MatchTable":
                return PBOpKind::Stmt_MatchTable;
            case "Terminal_Exit":
                return PBOpKind::Terminal_Exit;
            case "Terminal_Return":
                return PBOpKind::Terminal_Return;
            case "Terminal_Throw":
                return PBOpKind::Terminal_Throw;
            case "Terminal_GlobalVar":
                return PBOpKind::Terminal_GlobalVar;
            case "Terminal_Echo":
                return PBOpKind::Terminal_Echo;
            case "Terminal_MatchError":
                return PBOpKind::Terminal_MatchError;
            case "Terminal_StaticVar":
                return PBOpKind::Terminal_StaticVar;
            case "Terminal_Const":
                return PBOpKind::Terminal_Const;
            case "Terminal_Unset":
                return PBOpKind::Terminal_Unset;


            default:
                throw new LogicException(
                    "Unknown opkind rendering : " . $opkind
                );
        }
    }

    public function printScript(Script $script): PBScript
    {
        $protoscript = new PBScript();
        $functions = [];
        $functions[] = $this->printFunc($script->main);

        foreach ($script->functions as $func) {
            $functions[] =  $this->printFunc($func);
        }

        $protoscript->setFunctions($functions);
        return $protoscript;
    }

    public function printFunc(Func $func): PBFunction
    {
        $function = new PBFunction();
        $function->setName($func->name);
        if ($func->class) {
            $function->setClass($func->class->name);
        }

        $function->setReturnType($this->renderType($func->returnType));

        $pbblocks = [];
        $rendered = $this->render($func);
        foreach ($rendered['blocks'] as $block) {
            $pbblock = new PBBlock();
            $pbblock->setId($rendered['blockIds'][$block]);

            $pbblockParents = [];
            foreach ($block->parents as $prev) {
                if ($rendered['blockIds']->offsetExists($prev)) {
                    $pbblockParents[] = $rendered['blockIds'][$prev];
                }
            }
            $pbblock->setParentIds($pbblockParents);

            if ($block->catchTarget !== null) {
                $pbcatchTargets = [];
                foreach ($block->catchTarget->catches as $catch) {
                    $pbcatchTarget = new PBCatchTarget();
                    $pbcatchTarget->setType($this->renderType($catch['type']));
                    $pbcatchTarget->setVar($this->renderOperand($catch['var']));
                    $pbcatchTarget->setBlockId($rendered['blockIds'][$catch['block']]);
                    $pbcatchTargets[] = $pbcatchTarget;
                }

                if ($rendered['blockIds']->offsetExists($block->catchTarget->finally)) {
                    $pbfinallyTarget = new PBFinallyTarget();
                    $pbfinallyTarget->setBlockId($rendered['blockIds'][$block->catchTarget->finally]);
                    $pbblock->setFinallyTarget($pbfinallyTarget);
                }

                $pbblock->setCatchTargets($pbcatchTargets);
            }

            $ops = $rendered['blocks'][$block];
            $pbops = [];
            foreach ($ops as $op) {
                $pbop = new PBOp();
                $pbop->setKind(Protobuf::getEnumKindFromString($op['kind']));
                $pbop->setLabel($op['label']);

                $childpbblocks = [];
                foreach ($op['childBlocks'] as $child) {
                    $childpbblocks[$child['name']] = $rendered['blockIds'][$child['block']];
                }

                $pbop->setChildBlocks($childpbblocks);
                $pbops[] = $pbop;
            }

            $pbblock->setOps($pbops);
            $pbblocks[] = $pbblock;
        }

        $function->setBlocks($pbblocks);

        return $function;
    }

    public function renderOperand(Operand $var): PBOneOperand
    {
        foreach ($this->renderers as $renderer) {
            $result = $renderer->renderOperand($var);
            if ($result !== null) {
                $kind = $result['kind'];
                $type = $result['type'];

                if ($kind == "NULL") {
                    $pboneOperand = new PBOneOperand();
                    $pboneOperand->setNull(new PBNull());
                    return $pboneOperand;
                } elseif ($kind == "LITERAL") {
                    $pboneOperand = new PBOneOperand();
                    $literaloperand = new PBLiteral();

                    $value = $result["value"];
                    $type = gettype($value);
                    if (is_bool($value)) {
                        $value = $value ? "true" : "false";
                    } else {
                        $value = strval($value);
                    }

                    $type_ = PBScalarType::String;
                    if ($type == "boolean") {
                        $type_ = PBScalarType::Bool;
                    } elseif ($type == "double") {
                        $type_ = PBScalarType::Float;
                    } elseif ($type == "integer") {
                        $type_ = PBScalarType::Int;
                    }

                    $literaloperand->setType($type_);
                    $literaloperand->setValue(mb_convert_encoding($value, "UTF-8", "ISO-8859-1"));
                    $pboneOperand->setLiteral($literaloperand);
                    return $pboneOperand;
                } elseif ($kind == "TEMP") {
                    $pboneOperand = new PBOneOperand();
                    $tempoperand = new PBTemporary();
                    $tempoperand->setType($type);
                    $tempoperand->setId($result["id"]);
                    if ($result["original"] && $result["original"]->hasVariable()) {
                        $tempoperand->setOriginal($result["original"]->getVariable());
                    }
                    $pboneOperand->setTemporary($tempoperand);
                    return $pboneOperand;
                } elseif ($kind == "VARIABLE") {
                    $pboneOperand = new PBOneOperand();
                    $varoperand = new PBVariable();
                    $varoperand->setType($type);
                    $varoperand->setName("$" . $result["name"]);
                    if (!empty($result["scope"])) {
                        $varoperand->setScope($result["scope"]);
                    }
                    $varoperand->setReference($result["reference"]);
                    $pboneOperand->setVariable($varoperand);
                    return $pboneOperand;
                }
            }
        }

        throw new LogicException("Unknown operand rendering: " . get_class($var));
    }

    public function renderScalar(string|float|int|bool $value): PBScalar
    {
        if (is_bool($value)) {
            $scalar = new PBScalar();
            $scalar->setBool($value);
            return $scalar;
        } elseif (is_float($value)) {
            $scalar = new PBScalar();
            $scalar->setFloat($value);
            return $scalar;
        } elseif (is_int($value)) {
            $scalar = new PBScalar();
            $scalar->setInt($value);
            return $scalar;
        }

        $scalar = new PBScalar();
        $scalar->setString(mb_convert_encoding($value, "UTF-8", "ISO-8859-1"));
        return $scalar;
    }

    public function renderOpLabelValue(mixed $value): PBOneLabelType
    {
        $result = new PBOneLabelType();

        if (is_array($value)) {
            $maplabel = new PBMapLabel();
            $map = [];
            foreach ($value as $k => $v) {
                $map[$k] = $this->renderOpLabelValue($v);
            }

            $maplabel->setValue($map);
            $result->setMap($maplabel);
        } elseif ($value instanceof PBOneOperand) {
            $result->setOperand($value);
        } else {
            $scalar = $this->renderScalar($value);
            if ($scalar) {
                $result->setScalar($scalar);
            }
        }

        return $result;
    }

    public function renderOpLabel(array $desc): PBMapLabel
    {
        unset($desc['childblocks']);

        foreach ($desc as $name => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $map[$k] = $this->renderOpLabelValue($v);
                }
            } else {
                $stringlabel = new PBOneLabelType();
                $scalar = new PBScalar();
                $scalar->setString($val);
                $stringlabel->setScalar($scalar);
                $map[$name] = $stringlabel;
            }
        }

        $result = new PBMapLabel();
        $result->setValue($map);

        return $result;
    }
}
